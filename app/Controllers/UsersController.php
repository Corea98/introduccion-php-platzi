<?php

namespace App\Controllers;

use Respect\Validation\Validator;
use App\Models\User;

class UsersController extends BaseController {
    public function getAddUserAction ($request) {
        $responseMessage = '';

        if ($request->getMethod() == 'POST') {
            $jobValidator = Validator::key('email', Validator::stringType()->notEmpty())
                  ->key('password', Validator::stringType()->notEmpty());

            try {
                $postData = $request->getParsedBody();
                $jobValidator-> assert($postData);

                $user = new User();
                $user->email = $postData['email'];
                $user->password = password_hash($postData['password'], PASSWORD_DEFAULT);
                $user->save();

                $responseMessage = 'Saved';
            } catch (\Exception $e) {
                $responseMessage = $e->getMessage();
            }
        }

        return $this->renderHTML('addUser.twig', [
            'responseMessage' => $responseMessage
        ]);
    }
}