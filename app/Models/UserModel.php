<?php

    namespace App\Models;

    use CodeIgniter\Model;

    class UserModel extends Model {

        protected $table = 'users';

        public function connectUser($mail, $password){

            $login = $this->where('email', $mail)->first();
            if ($login && $login['mot_de_passe'] === $password){
                return true;
            }

            return false;

        }

    }

?>