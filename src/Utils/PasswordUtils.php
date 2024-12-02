<?php

namespace App\Utils;

class PasswordUtils
{
    public static function isPasswordValid(?string $password): array
    {
        if (strlen($password) < 8) {
            return ["valid" => false, "message" => "Le mot de passe doit comporter au moins 8 caractères."];
        }

        if (strlen($password) > 48) {
            return ["valid" => false, "message" => "Le mot de passe ne peut pas comporter plus de 48 caractères."];
        }

        return ["valid" => true, "message" => "Mot de passe valide."];
    }
}
