<?php

namespace App\Domains\ActivityLog\Enums;

enum ActivityType: string
{
    case StudentRegistered = 'student.registered';
    case StudentLoginSuccess = 'student.login.success';
    case StudentLoginFailed = 'student.login.failed';
    case StudentLoggedOut = 'student.logged.out';
    case StudentVerificationSent = 'student.verification.sent';
    case StudentVerificationVerified = 'student.verification.verified';
    case StudentVerificationFailed = 'student.verification.failed';
    case StudentContactChanged = 'student.contact.changed';
    case StudentContactDetailsAdded = 'student.contact.details.added';
    case StudentPersonalDetailsUpdated = 'student.personal.details.updated';
    case ProfileFieldsCompleted = 'profile.fields.completed';
    case ProfileFieldCreated = 'admin.profile.field.created';
    case ProfileFieldUpdated = 'admin.profile.field.updated';
    case ProfileFieldDeleted = 'admin.profile.field.deleted';

    case CourseCreated = 'course.created';
    case CourseUpdated = 'course.updated';
    case CourseDeleted = 'course.deleted';
    case CoursePurchased = 'course.purchased';
    case StudentEnrolled = 'course.student.enrolled';
    case StudentUnenrolled = 'course.student.unenrolled';

    case StudentCreatedByAdmin = 'admin.student.created';
    case StudentUpdatedByAdmin = 'admin.student.updated';
    case StudentDeletedByAdmin = 'admin.student.deleted';
    case StudentDeletedSelf = 'student.deleted.self';
    case StudentRestoredByAdmin = 'admin.student.restored';
    case StudentAssignedToCourse = 'admin.student.assigned_to_course';
    case StudentUnenrolledFromCourse = 'admin.student.unenrolled_from_course';

    case UserCreated = 'admin.user.created';
    case UserUpdated = 'admin.user.updated';
    case UserDeleted = 'admin.user.deleted';
    case UserStatusChanged = 'admin.user.status_changed';

    case TeacherCreated = 'admin.teacher.created';
    case TeacherUpdated = 'admin.teacher.updated';
    case TeacherDeleted = 'admin.teacher.deleted';

    case TransactionCreated = 'transaction.created';
    case TransactionCompleted = 'transaction.completed';
    case TransactionFailed = 'transaction.failed';

    case WebinarCreated = 'webinar.created';
    case WebinarUpdated = 'webinar.updated';
    case WebinarDeleted = 'webinar.deleted';
    case StudentAssignedToWebinar = 'admin.student.assigned_to_webinar';
    case StudentUnregisteredFromWebinar = 'admin.student.unregistered_from_webinar';

    case HomeworkSubmitted = 'homework.submitted';
    case HomeworkReviewed = 'homework.reviewed';

    case ModuleCreated = 'module.created';
    case ModuleUpdated = 'module.updated';
    case ModuleDeleted = 'module.deleted';
    case ModulesReordered = 'modules.reordered';

    case LessonCreated = 'lesson.created';
    case LessonUpdated = 'lesson.updated';
    case LessonDeleted = 'lesson.deleted';
    case LessonCompleted = 'lesson.completed';
    case LessonsReordered = 'lessons.reordered';

    case QuizCreated = 'quiz.created';
    case QuizUpdated = 'quiz.updated';
    case QuizDeleted = 'quiz.deleted';
    case QuizQuestionCreated = 'quiz.question.created';
    case QuizQuestionUpdated = 'quiz.question.updated';
    case QuizQuestionDeleted = 'quiz.question.deleted';
    case QuizSubmitted = 'quiz.submitted';
    case QuestionsReordered = 'questions.reordered';

    case CertificateIssued = 'certificate.issued';
    case CertificatePublished = 'certificate.published';
    case CertificateRevoked = 'certificate.revoked';
    case CertificateRestored = 'certificate.restored';

    case CommentCreated = 'comment.created';
    case CommentDeleted = 'comment.deleted';
    case CommentReplied = 'comment.replied';

    case StudentGroupCreated = 'student_group.created';
    case StudentGroupUpdated = 'student_group.updated';
    case StudentGroupDeleted = 'student_group.deleted';
    case StudentAddedToGroup = 'student_group.student_added';
    case StudentRemovedFromGroup = 'student_group.student_removed';

    case DiscountAssigned = 'discount.assigned';
    case DiscountRemoved = 'discount.removed';

    case AdminLoginSuccess = 'admin.login.success';
    case AdminLoginFailed = 'admin.login.failed';
    case AdminLoggedOut = 'admin.logged.out';

    case SystemError = 'system.error';
}
