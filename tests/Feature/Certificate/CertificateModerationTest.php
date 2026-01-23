<?php

namespace Tests\Feature\Certificate;

use App\Domains\Certificate\Actions\IssueCertificateAction;
use App\Domains\Certificate\Actions\PublishCertificateAction;
use App\Domains\Certificate\Actions\RevokeCertificateAction;
use App\Domains\Certificate\Enums\CertificateStatus;
use App\Domains\Certificate\Models\Certificate;
use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use App\Models\User;
use App\Notifications\CertificateIssuedNotification;
use App\Notifications\CertificatePublishedNotification;
use Database\Factories\CertificateFactory;
use Database\Factories\CourseFactory;
use Database\Factories\StudentFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CertificateModerationTest extends TestCase
{
    use RefreshDatabase;

    private Student $student;

    private Course $courseWithApproval;

    private Course $courseWithoutApproval;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->student = StudentFactory::new()->create();
        $this->admin = UserFactory::new()->admin()->create();

        $this->courseWithApproval = CourseFactory::new()->create([
            'requires_certificate_approval' => true,
        ]);

        $this->courseWithoutApproval = CourseFactory::new()->create([
            'requires_certificate_approval' => false,
        ]);
    }

    public function test_certificate_with_approval_required_is_pending(): void
    {
        Notification::fake();

        $certificate = app(IssueCertificateAction::class)(
            $this->student,
            $this->courseWithApproval
        );

        $this->assertTrue($certificate->isPending());
        $this->assertFalse($certificate->isPublished());
        $this->assertNull($certificate->published_at);
        $this->assertEquals(CertificateStatus::Pending, $certificate->getStatus());

        Notification::assertNotSentTo($this->student, CertificateIssuedNotification::class);
    }

    public function test_certificate_without_approval_required_is_auto_published(): void
    {
        Notification::fake();

        $certificate = app(IssueCertificateAction::class)(
            $this->student,
            $this->courseWithoutApproval
        );

        $this->assertTrue($certificate->isPublished());
        $this->assertFalse($certificate->isPending());
        $this->assertNotNull($certificate->published_at);
        $this->assertEquals(CertificateStatus::Published, $certificate->getStatus());

        Notification::assertSentTo($this->student, CertificateIssuedNotification::class);
    }

    public function test_publishing_sets_published_at_and_published_by(): void
    {
        $certificate = CertificateFactory::new()
            ->forStudent($this->student)
            ->forCourse($this->courseWithApproval)
            ->pending()
            ->create();

        $this->assertTrue($certificate->isPending());

        $publishedCertificate = app(PublishCertificateAction::class)($certificate, $this->admin);

        $this->assertTrue($publishedCertificate->isPublished());
        $this->assertNotNull($publishedCertificate->published_at);
        $this->assertEquals($this->admin->id, $publishedCertificate->published_by);
    }

    public function test_revoking_sets_revoked_at_and_revoked_by(): void
    {
        $certificate = CertificateFactory::new()
            ->forStudent($this->student)
            ->forCourse($this->courseWithoutApproval)
            ->published()
            ->create();

        $this->assertTrue($certificate->isPublished());

        $revokedCertificate = app(RevokeCertificateAction::class)($certificate, $this->admin);

        $this->assertTrue($revokedCertificate->isRevoked());
        $this->assertNotNull($revokedCertificate->revoked_at);
        $this->assertEquals($this->admin->id, $revokedCertificate->revoked_by);
        $this->assertEquals(CertificateStatus::Revoked, $revokedCertificate->getStatus());
    }

    public function test_student_cannot_see_unpublished_certificate(): void
    {
        CertificateFactory::new()
            ->forStudent($this->student)
            ->forCourse($this->courseWithApproval)
            ->pending()
            ->create();

        $visibleCertificates = Certificate::forStudent($this->student->id)
            ->visibleToStudent()
            ->get();

        $this->assertCount(0, $visibleCertificates);
    }

    public function test_student_can_see_published_certificate(): void
    {
        CertificateFactory::new()
            ->forStudent($this->student)
            ->forCourse($this->courseWithoutApproval)
            ->published()
            ->create();

        $visibleCertificates = Certificate::forStudent($this->student->id)
            ->visibleToStudent()
            ->get();

        $this->assertCount(1, $visibleCertificates);
    }

    public function test_notification_sent_on_publish(): void
    {
        Notification::fake();

        $certificate = CertificateFactory::new()
            ->forStudent($this->student)
            ->forCourse($this->courseWithApproval)
            ->pending()
            ->create();

        $this->student->notify(new CertificatePublishedNotification($certificate));

        Notification::assertSentTo($this->student, CertificatePublishedNotification::class);
    }

    public function test_certificate_status_returns_correct_enum(): void
    {
        $pendingCertificate = CertificateFactory::new()
            ->forStudent($this->student)
            ->forCourse($this->courseWithApproval)
            ->pending()
            ->create();

        $publishedCertificate = CertificateFactory::new()
            ->forStudent($this->student)
            ->forCourse(CourseFactory::new()->create())
            ->published()
            ->create();

        $revokedCertificate = CertificateFactory::new()
            ->forStudent($this->student)
            ->forCourse(CourseFactory::new()->create())
            ->revoked()
            ->create();

        $this->assertEquals(CertificateStatus::Pending, $pendingCertificate->getStatus());
        $this->assertEquals(CertificateStatus::Published, $publishedCertificate->getStatus());
        $this->assertEquals(CertificateStatus::Revoked, $revokedCertificate->getStatus());
    }

    public function test_republishing_revoked_certificate_clears_revoked_fields(): void
    {
        $certificate = CertificateFactory::new()
            ->forStudent($this->student)
            ->forCourse($this->courseWithApproval)
            ->revoked()
            ->create([
                'published_at' => now()->subDay(),
                'published_by' => $this->admin->id,
            ]);

        $this->assertTrue($certificate->isRevoked());

        $republishedCertificate = app(PublishCertificateAction::class)($certificate, $this->admin);

        $this->assertTrue($republishedCertificate->isPublished());
        $this->assertFalse($republishedCertificate->isRevoked());
        $this->assertNull($republishedCertificate->revoked_at);
        $this->assertNull($republishedCertificate->revoked_by);
    }

    public function test_is_visible_to_student_returns_correct_value(): void
    {
        $pendingCertificate = CertificateFactory::new()
            ->forStudent($this->student)
            ->forCourse($this->courseWithApproval)
            ->pending()
            ->create();

        $publishedCertificate = CertificateFactory::new()
            ->forStudent($this->student)
            ->forCourse(CourseFactory::new()->create())
            ->published()
            ->create();

        $revokedCertificate = CertificateFactory::new()
            ->forStudent($this->student)
            ->forCourse(CourseFactory::new()->create())
            ->revoked()
            ->create();

        $this->assertFalse($pendingCertificate->isVisibleToStudent());
        $this->assertTrue($publishedCertificate->isVisibleToStudent());
        $this->assertFalse($revokedCertificate->isVisibleToStudent());
    }
}
