<?php

namespace Salberts\Bundle\Recaptcha2Bundle\Bridge\Form\Extension;

use Silex\Application;
use Symfony\Component\Form\AbstractExtension;
use Salberts\Bundle\Recaptcha2Bundle\Form\Type\RecaptchaType;

/**
 * Extends form to register captcha type
 */
class RecaptchaExtension extends AbstractExtension
{
    /**
     * Container
     *
     * @var \Silex\Application
     */
    private $app;

    /**
     * Constructor
     *
     * @param \Silex\Application $app container
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Register the captche form type
     *
     * @return array
     */
    protected function loadTypes()
    {
        return array(
            new RecaptchaType(
                $this->app['salberts_recaptcha2.public_key'],
                $this->app['salberts_recaptcha2.enabled'],
                $this->app['salberts_recaptcha2.ajax'],
                $this->app['salberts_recaptcha2.locale_key']
            )
        );
    }
}
