<!DOCTYPE html "-//w3c//dtd xhtml 1.0 transitional //en" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
    <title>Email du {{ config('settings.app_name_' . config('app.locale')) }}</title>
</head>
<body style="width: 100% !important;min-width: 100%;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100% !important;margin: 0;padding: 0;background-color: #FFFFFF">

<style>
    body {
        font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
    }
    table td {
        vertical-align: middle;
    }
    .row {
        width: 100%;
    }
    .row .header, .row .footer, .row .header td, .row .footer td {
        background-color: #000;
        color: #bbbbbb;
    }
    .row .header, .row .content, .row .footer {
        padding: 30px 0;
    }
    .row .header .logo {
        width: 70px;
    }
    .row .header .title {
        padding-left: 5px;
    }
    .row .info {
        background-color: #EDEDED;
        padding: 15px 0;
    }
    .row .info p {
        font-size: 12px;
        color: #555555;
    }
    h1, h2, h3 {
        font-weight: normal;
    }
    h1 {
        margin: 0;
        font-size: 20px;
        text-transform: uppercase;
        color: #fff;
    }
    h2 {
        font-size: 22px;;
    }
    h3 {
        font-size: 18px;
    }
    .title p {
        text-transform: uppercase;
        margin: 0;
        font-size: 14px;
        color: #aaa;
    }
    .halign-center {
        margin: 0 auto;
    }
    .w600 {
        width: 660px;
        min-width: 660px;
    }
    .text-center {
        text-align: center;
    }
    a {
        color: #337ab7;
        padding: 0;
        text-decoration: none;
    }
    p {
        font-size: 14px;
        color: #606060;
    }
    .contact {
        padding: 10px 0;
    }
    .contact p {
        color: inherit;
        margin: 0;
        line-height: 18px;
        text-transform: uppercase;
    }
    .contact .address {
        padding-right: 10px;
    }
    .contact .address p {
        text-align: right;
    }
    .contact .logo {
        padding: 0 10px;
    }
    .contact .social {
        padding-left: 10px;
        width: 170px;
    }
    .contact .social p {
        margin-bottom: 5px;
    }
    .contact .social a {
        margin: 3px 3px;
        display: inline-block;
    }
    .contact .delimiter {
        vertical-align: middle;
    }
    .contact .delimiter div {
        height: 50px;
        width: 1px;
        border-right: dashed 1px #bbbbbb;
    }
    .btn {
        display: inline-block;
        margin-bottom: 0;
        font-weight: normal;
        text-align: center;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        white-space: nowrap;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857;
        border-radius: 4px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        color: #fff;
        background-color: #337ab7;
        border-color: #2e6da4;
    }
</style>

<table class="row">
    <tr>
        <td class="header">
            <table class="halign-center w600">
                <tr>
                    <td class="logo">
                        @if(config('settings.logo_light'))
                            <img width="70" height="70" src="{{ ImageManager::imagePath(config('image.settings.public_path'), config('settings.logo_light'), 'logo', 'header') }}">
                        @endif
                    </td>
                    <td class="title">
                        <h1>{{ config('settings.app_name_' . config('app.locale')) }}</h1>
                        <p>{{ config('settings.app_slogan_' . config('app.locale')) }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="info">
            <table class="halign-center w600">
                <tr>
                    <td>
                        <p>{{ trans('emails.template.no_reply') }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table class="row">
    <tr>
        <td class="content">
            <table class="halign-center w600">
                <tr>
                    <td>
                        @yield('content')
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table class="row">
    <tr>
        <td class="info">
            <table class="halign-center w600">
                <tr>
                    <td>
                        <p>{!! trans('emails.template.problem', ['email' => config('settings.support_email')]) !!}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="footer">
            <table class="halign-center w600">
                <tr>
                    <td class="contact">
                        <table>
                            <tr>
                                <td class="address">
                                    <p>{{ config('settings.app_name_' . config('app.locale')) }}</p>
                                    <p>{{ config('settings.phone_number') }}</p>
                                    <p>
                                        <a href="mailto:{{ config('settings.contact_email') }}">
                                            {{ config('settings.contact_email') }}
                                        </a>
                                    <p>
                                    <p>{{ config('settings.address') }} {{ config('settings.zip_code') }} {{ config('settings.city') }}</p>
                                </td>
                                <td class="delimiter">
                                    <div></div>
                                </td>
                                <td class="logo text-center">
                                    @if(config('settings.logo_light'))
                                        <img width="70" height="70" src="{{ ImageManager::imagePath(config('image.settings.public_path'), config('settings.logo_light'), 'logo', 'header') }}">
                                    @endif
                                </td>
                                <td class="delimiter">
                                    <div></div>
                                </td>
                                <td class="social">
                                    <a href="{{ url('/') }}">
                                        <img width="32" height="32" src="{{ url('/img/mail/website.png') }}">
                                    </a>
                                    @if(config('settings.facebook'))
                                        <a href="{{ config('settings.facebook') }}">
                                            <img width="32" height="32" src="{{ url('/img/mail/facebook.png') }}">
                                        </a>
                                    @endif
                                    @if(config('settings.twitter'))
                                        <a href="{{ config('settings.twitter') }}">
                                            <img width="32" height="32" src="{{ url('/img/mail/twitter.png') }}">
                                        </a>
                                    @endif
                                    @if(config('settings.google_plus'))
                                        <a href="{{ config('settings.google_plus') }}">
                                            <img width="32" height="32" src="{{ url('/img/mail/googleplus.png') }}">
                                        </a>
                                    @endif
                                    @if(config('settings.linkedin'))
                                        <a href="{{ config('settings.linkedin') }}">
                                            <img width="32" height="32" src="{{ url('/img/mail/linkedin.png') }}">
                                        </a>
                                    @endif
                                    @if(config('settings.pinterest'))
                                        <a href="{{ config('settings.pinterest') }}">
                                            <img width="32" height="32" src="{{ url('/img/mail/pinterest.png') }}">
                                        </a>
                                    @endif
                                    @if(config('settings.youtube'))
                                        <a href="{{ config('settings.youtube') }}">
                                            <img width="32" height="32" src="{{ url('/img/mail/youtube.png') }}">
                                        </a>
                                    @endif
                                    @if(config('settings.contact_email'))
                                        <a href="mailto:{{ config('settings.contact_email') }}">
                                            <img width="32" height="32" src="{{ url('/img/mail/mail.png') }}">
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>