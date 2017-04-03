<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Validation\ValidationException;
use Illuminate\Session\TokenMismatchException;
use JavaScript;
use Modal;
use Symfony\Component\HttpKernel\Exception\HttpException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($this->isHttpException($exception)) {
            return $this->renderHttpException($exception);
        }
    
        // token mismatch exception handling
        if ($exception instanceof TokenMismatchException) {
            $this->renderTokenMismatchException($request, $exception);
        }
        
        return parent::render($request, $exception);
        
    }
    
    /**
     * Special treatment for the TokenMismatchException.
     *
     * @param                        $request
     * @param TokenMismatchException $exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function renderTokenMismatchException($request, TokenMismatchException $exception)
    {
        // we notify the current user
        Modal::alert([
            trans('errors.token_mismatch'),
        ], 'error');
        
        // we redirect the user
        return redirect()->back()->withInput($request->all());
    }
    
    /**
     * Special treatment for the HttpExceptions.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpException $exception)
    {
        // load base JS
        JavaScript::put([
            'base_url'  => url('/'),
            'site_name' => config('settings.app_name_' . config('app.locale')),
        ]);
        
        $meta_title = trans('errors.' . $exception->getStatusCode() . '.title');
        $meta_description = trans('errors.' . $exception->getStatusCode() . '.message');
        $meta_keywords = null;
        
        // SEO Meta settings
        $seo_meta['page_title'] = $meta_title;
        $seo_meta['description'] = $meta_description;
        $seo_meta['keywords'] = $meta_keywords;
        
        // og meta settings
        $og_meta['og:title'] = $meta_title;
        $og_meta['og:description'] = $meta_description;
        $og_meta['og:type'] = 'article';
        
        // twitter meta settings
        $twitter_meta['twitter:title'] = $meta_title;
        $twitter_meta['twitter:description'] = $meta_description;
        
        $data = [
            'code'         => $exception->getStatusCode(),
            'seo_meta'     => $seo_meta,
            'og_meta'      => $og_meta,
            'twitter_meta' => $twitter_meta,
            'css'          => elixir('css/app.error.css'),
        ];
        
        return response()->view('templates.common.errors.errors', $data)
            ->setStatusCode($exception->getStatusCode());
    }

}
