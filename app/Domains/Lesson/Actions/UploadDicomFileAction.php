<?php

namespace App\Domains\Lesson\Actions;

use App\Domains\Lesson\Enums\DicomSourceType;
use App\Domains\Lesson\Models\Lesson;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadDicomFileAction
{
    public static function execute(Lesson $lesson, UploadedFile $file): Lesson
    {
        self::validateDicomFile($file);

        if ($lesson->dicom_file_path) {
            Storage::disk('private')->delete($lesson->dicom_file_path);
        }

        $path = $file->store('dicom', 'private');

        $metadata = self::extractMetadata($file);

        $lesson->update([
            'dicom_source_type' => DicomSourceType::File,
            'dicom_file_path' => $path,
            'dicom_url' => null,
            'dicom_metadata' => $metadata,
        ]);

        return $lesson;
    }

    private static function validateDicomFile(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['dcm', 'dicom'])) {
            throw new \InvalidArgumentException('Invalid file extension. Only .dcm and .dicom files are allowed.');
        }

        $maxSize = 100 * 1024 * 1024;
        if ($file->getSize() > $maxSize) {
            throw new \InvalidArgumentException('File size exceeds maximum allowed (100MB).');
        }

        $handle = fopen($file->getPathname(), 'rb');
        if (!$handle) {
            throw new \InvalidArgumentException('Unable to read file.');
        }

        fseek($handle, 128);
        $magic = fread($handle, 4);
        fclose($handle);

        if ($magic !== 'DICM') {
            throw new \InvalidArgumentException('Invalid DICOM file. Missing DICM magic bytes.');
        }
    }

    private static function extractMetadata(UploadedFile $file): array
    {
        $handle = fopen($file->getPathname(), 'rb');
        if (!$handle) {
            return self::defaultMetadata($file);
        }

        $metadata = [
            'file_size_bytes' => $file->getSize(),
            'frame_count' => 1,
            'is_multiframe' => false,
            'modality' => null,
            'rows' => null,
            'columns' => null,
        ];

        fseek($handle, 132);

        $maxRead = min($file->getSize(), 8192);
        $headerData = fread($handle, $maxRead - 132);
        fclose($handle);

        if (($pos = strpos($headerData, "\x08\x00\x60\x00")) !== false) {
            $valuePos = $pos + 8;
            if (strlen($headerData) > $valuePos + 2) {
                $modality = trim(substr($headerData, $valuePos, 16));
                $modality = preg_replace('/[^A-Z]/', '', $modality);
                if (strlen($modality) >= 2 && strlen($modality) <= 4) {
                    $metadata['modality'] = $modality;
                }
            }
        }

        if (($pos = strpos($headerData, "\x28\x00\x08\x00")) !== false) {
            $valuePos = $pos + 8;
            if (strlen($headerData) > $valuePos + 4) {
                $frames = unpack('V', substr($headerData, $valuePos, 4));
                if ($frames && $frames[1] > 0 && $frames[1] < 10000) {
                    $metadata['frame_count'] = $frames[1];
                    $metadata['is_multiframe'] = $frames[1] > 1;
                }
            }
        }

        return $metadata;
    }

    private static function defaultMetadata(UploadedFile $file): array
    {
        return [
            'file_size_bytes' => $file->getSize(),
            'frame_count' => 1,
            'is_multiframe' => false,
            'modality' => null,
            'rows' => null,
            'columns' => null,
        ];
    }
}
