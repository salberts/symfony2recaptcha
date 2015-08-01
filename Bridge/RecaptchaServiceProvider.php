<?php

namespace Salberts\Bundle\Recaptcha2Bundle\Bridge;

use Silex\Application;
use Silex\ServiceProviderInterface;
use S\Bundle\RecaptchaBundle\Validator\Constraints\IsTrueValidator;

/**
 * Silex Service Provider
 * Inject recaptcha configuration in pimple
 */
class RecaptchaServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        // Parameters
        $app['salberts_recaptcha2.public_key'] = null;
        $app['salberts_recaptcha2.private_key'] = null;
        $app['salberts_recaptcha2.locale_key'] = $app['locale'];
        $app['salberts_recaptcha2.enabled'] = true;
        $app['salberts_recaptcha2.ajax'] = false;

        // add loader for EWZ Template
        if (isset($app['twig'])) {
            $path = dirname(__FILE__).'/../Resources/views/Form';
            $app['twig.loader']->addLoader(new \Twig_Loader_Filesystem($path));

            $app['twig.form.templates'] = array_merge(
                $app['twig.form.templates'],
                array('salberts_recaptcha2_widget.html.twig')
            );
        }

        // Register recaptcha form type
        if (isset($app['form.extensions'])) {
            $app['form.extensions'] = $app->share($app->extend('form.extensions',
                function($extensions) use ($app) {
                    $extensions[] = new Form\Extension\RecaptchaExtension($app);

                    return $extensions;
            }));
        }

        // Register recaptcha validator constraint
        if (isset($app['validator.validator_factory'])) {
            $app['salberts_recaptcha2.true'] = $app->share(function ($app) {
                $validator = new IsTrueValidator(
                    $app['salberts_recaptcha2.enabled'],
                    $app['salberts_recaptcha2.private_key'],
                    $app['request_stack']
                );

                return $validator;
            });

            $app['validator.validator_service_ids'] =
                    isset($app['validator.validator_service_ids']) ? $app['validator.validator_service_ids'] : array();
            $app['validator.validator_service_ids'] = array_merge(
                $app['validator.validator_service_ids'],
                array('salberts_recaptcha2.true' => 'salberts_recaptcha2.true')
            );
        }

        // Register translation files
        if (isset($app['translator'])) {
            $app['translator']->addResource(
                'xliff',
                dirname(__FILE__).'/../Resources/translations/validators.'.$app['locale'].'.xlf',
                $app['locale'],
                'validators'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
