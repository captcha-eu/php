<?php

namespace CaptchaEU;

class Service
{
    protected $publicKey;
    protected $privateKey;
    protected $client;
    protected $endpoint;


    public function __construct(string $publicKey, string $privateKey, string $endpoint)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->endpoint = $endpoint;
    }



    public function protectForm(string $id): string
    {

        return '<script>
                    CaptchaDOMReady(function() {
                        var f = document.getElementById("' . $id . '");
                        KROT.interceptForm(f);
                    });
                </script>';
    }

    public function script(?string $locale = null, bool $render = false, ?string $onload = null, ?string $recaptchacompat = null): string
    {
        return '
        <script src="' . $this->endpoint . '/sdk.js"></script>
        <script>
        var CaptchaDOMReady = function (callback) {
            document.readyState === "interactive" || document.readyState === "complete"
              ? callback()
              : document.addEventListener("DOMContentLoaded", callback);
          };
          CaptchaDOMReady(function() {
            KROT.setup("' . $this->publicKey . '");
          });
                </script>';
    }

    public function validateSolution($solution) {
        $ch = curl_init($this->endpoint . "/validate");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $solution);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Rest-Key: ' . $this->privateKey));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $resultObject = json_decode($result);
        if ($resultObject->success) {
          return true;
        } else {
          return false;
        }
    }
    /**
     * Validate the user response.
     */
    public function validate(?string $solution): bool
    {
        if($solution == "") {
            return false;
        }
        $r = $this->validateSolution($solution);
        return $r;
    }


}
