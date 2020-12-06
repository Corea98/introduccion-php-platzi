<?php

namespace App\Controllers;

use App\Models\Job;
use Respect\Validation\Validator;

class JobsController extends BaseController {
    public function getAddJobAction ($request) {
        $responseMessage = '';

        if ($request->getMethod() == 'POST') {
            $jobValidator = Validator::key('title', Validator::stringType()->notEmpty())
                  ->key('description', Validator::stringType()->notEmpty());

            try {
                $postData = $request->getParsedBody();
                $jobValidator->assert($postData);

                $files = $request->getUploadedFiles();
                $logo = $files['logo'];

                if ($logo->getError() == UPLOAD_ERR_OK) {
                    $filename = $logo->getClientFilename();
                    $logo->moveTo("uploads/$filename");
                }

                $job = new Job();
                $job->title = $postData['title'];
                $job->description = $postData['description'];
                $job->save();

                $responseMessage = 'Saved';
            } catch (\Exception $e) {
                $responseMessage = $e->getMessage();
            }
        }

        return $this->renderHTML('addJob.twig', [
            'responseMessage' => $responseMessage
        ]);
    }
}