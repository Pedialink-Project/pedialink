<?php

namespace App\Services\Parent;

use App\Helpers\FileHandler;
use App\Helpers\FileValidator;
use App\Models\ParentM;

class VerificationService
{
    private function commonFileValidation(array $file)
    {
        $error = null;

        if (!FileValidator::isFileValid($file)) {
            $error = "File was not uploaded. Please upload a file!";
            return $error;
        }

        if (!FileValidator::isUploaded($file)) {
            $error = "Failed to upload file";
            return $error;
        }

        if (!FileValidator::validateSize($file,  20 * 1024 * 1024)) {
            $error = "File should be less than 20 MB";
            return $error;
        }

        return $error;
    }

    public function validateDocuments(
        array $birth_certificate,
        array $marriage_certificate,
        array $nic_copy
    )
    {
        $errors = [];

        $birthCertificateError = $this->commonFileValidation($birth_certificate);
        if ($birthCertificateError) {
            $errors['birth_certificate_error'] = $birthCertificateError;
        }

        $marriageCertificateError = $this->commonFileValidation($marriage_certificate);
        if ($marriageCertificateError) {
            $errors['marriage_certificate_error'] = $marriageCertificateError;
        }

        $niceCopyError = $this->commonFileValidation($nic_copy);
        if ($niceCopyError) {
            $errors['nic_copy_error'] = $niceCopyError;
        }

        return $errors;
    }

    public function uploadDocuments(
        array $birth_certificate,
        array $marriage_certificate,
        array $nic_copy
    )
    {
        $user = auth()->user();

        if ($user) {
            /**
             * @var ParentM
             */
            $parent = $user->getRole();
            $destination = "verification/{$user->id}/";
            $data = [
                'birth_certificate' => [
                    'fileName' => FileHandler::generateUniqueFilename(
                        $birth_certificate['name'],
                        $destination
                    ),
                    'file' => $birth_certificate,
                ],
                'marriage_certificate' => [
                    'fileName' => FileHandler::generateUniqueFilename(
                        $marriage_certificate['name'],
                        $destination
                    ),
                    'file' => $marriage_certificate,
                ],
                'nic_copy' => [
                    'fileName' => FileHandler::generateUniqueFilename(
                        $nic_copy['name'],
                        $destination
                    ),
                    'file' => $nic_copy
                ],
            ];

            $valid = false;
            foreach ($data as $file) {
                $valid = storage()
                    ->putFile('local', $file['file'], $file['fileName']);
            }

            if ($valid) {
                $parent->birth_certificate = $data['birth_certificate']['fileName'];
                $parent->marriage_certificate = $data['marriage_certificate']['fileName'];
                $parent->nic_copy = $data['nic_copy']['fileName'];
                $parent->save();
            }
            
        }
    }
}