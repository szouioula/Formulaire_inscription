<?php

use PHPUnit\Framework\TestCase;

class FormulaireValidation extends TestCase
{
    public function testPhoneValidation()
    {
        $validPhone = '1234567890';
        $invalidPhone = 'abcdef';

        $this->assertTrue(validatePhoneNumber($validPhone));
        $this->assertFalse(validatePhoneNumber($invalidPhone));
    }

    public function testEmailValidation()
    {
        $validEmail = 'test@example.com';
        $invalidEmail = 'invalid';

        $this->assertTrue(validateEmail($validEmail));
        $this->assertFalse(validateEmail($invalidEmail));
    }
}

function validatePhoneNumber($phone)
{
     // Supprimer tous les caractères non numériques 
     $phone = preg_replace('/[^0-9]/', '', $phone);

     // Vérifier si le numéro de téléphone a le bon format
     $pattern = '/^[0-9]{10}$/';
     if (!preg_match($pattern, $phone)) {
         return false; 
     }
 
     // Le numéro de téléphone est valide
     return true; 
}

function validateEmail($email)
{
    // Vérifier si l'adresse e-mail a le bon format

    $pattern = '/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/';
    if (!preg_match($pattern, $email)) {
        return false; 
    }
    
    // L'adresse e-mail est valide
    return true; 
}
