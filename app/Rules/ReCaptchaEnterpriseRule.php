<?php

namespace App\Rules;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;

class ReCaptchaEnterpriseRule implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $client = new Client();

        try{

            $response = $client->post($this->recaptchaEnterpriseVerificationUrl(), [
                'json' => [
                    'event' => [
                        'token' => $value,
                        'site_key' => $this->getSiteKey(),
                        'expectedAction' => request()->get('g-recaptcha-action', null),
                    ]
                ]
            ]);

            $content = json_decode($response->getBody()->getContents());

            return $content->tokenProperties->valid;

        }
        catch(\GuzzleHttp\Exception\RequestException $e){

            return false;

        }
        catch(Exception $e){

            return false;

        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Unable to validate recaptcha token';
    }

    private function recaptchaEnterpriseVerificationUrl(): string
    {
        return "https://recaptchaenterprise.googleapis.com/v1/projects/{$this->getProjectId()}/assessments?key={$this->getApiKey()}";
    }

    private function getProjectId(): ?string
    {
       return config('services.recaptcha_ent.project_id');
    }

    private function getApiKey(): ?string
    {
        return config('services.recaptcha_ent.api_key');
    }

    public function getSiteKey(): ?string
    {
        return config('services.recaptcha_ent.site_key');
    }

}
