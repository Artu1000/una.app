<!DOCTYPE html "-//w3c//dtd xhtml 1.0 transitional //en" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width">
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
        <title>Email du {{ config('app.name') }}</title>
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
                font-size: 22px;
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
                color: #aaaaaa;
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
            }
            .contact .social p {
                margin-bottom: 5px;
            }
            .contact .social a {
                margin: 0 10px 0 0;
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
                                <img width="70" height="66" src="{{ url(config('app.logo.small.light')) }}" alt="{{ config('app.name') }}">
                            </td>
                            <td class="title">
                                <h1>{{ config('app.name') }}</h1>
                                <p>Le plus grand club d'aviron universitaire de France</p>
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
                                <p>Cet email vous a été envoyé automatiquement, merci de ne pas y répondre</p>
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
                <td class="footer">
                    <table class="halign-center w600">
                        <tr>
                            <td class="contact">
                                <table>
                                    <tr>
                                        <td class="address">
                                            <p>{{ config('app.phone') }}</p>
                                            <p>
                                                <a href="mailto:{{ config('app.email.contact') }}" title="{{ config('app.name') }}">
                                                    {{ config('app.email.contact') }}
                                                </a>
                                            <p>
                                            <p>{{ config('app.address.number') }} {{ config('app.address.street') }} {{ config('app.address.postal_code') }} {{ config('app.address.city') }}</p>
                                        </td>
                                        <td class="delimiter">
                                            <div></div>
                                        </td>
                                        <td class="logo text-center">
                                            <img width="70" height="66" src="{{ url(config('app.logo.small.light')) }}" alt="{{ config('app.name') }}">
                                        </td>
                                        <td class="delimiter">
                                            <div></div>
                                        </td>
                                        <td class="social">
                                            <p>Nous suivre :</p>
                                            <a href="{{ config('app.social.facebook') }}" title="Facebook {{ config('app.name') }}">
                                                <img src="{{ url('/img/mail/facebook.png') }}" alt="Facebook {{ config('app.name') }}">
                                            </a>
                                            <a href="{{ config('app.social.twitter') }}" title="Twitter {{ config('app.name') }}">
                                                <img src="{{ url('/img/mail/twitter.png') }}" alt="Twitter {{ config('app.name') }}">
                                            </a>
                                            <a href="{{ config('app.social.google+') }}" title="Google+ {{ config('app.name') }}">
                                                <img src="{{ url('/img/mail/googleplus.png') }}" alt="Google+ {{ config('app.name') }}">
                                            </a>
                                            <a href="{{ config('app.social.youtube') }}" title="Chaîne Youtube {{ config('app.name') }}">
                                                <img src="{{ url('/img/mail/youtube.png') }}" alt="Chaîne Youtube {{ config('app.name') }}">
                                            </a>
                                            <a href="mailto:{{ config('app.email.contact') }}" title="Contacter le {{ config('app.name') }}">
                                                <img src="{{ url('/img/mail/mail.png') }}" alt="Contacter le {{ config('app.name') }}">
                                            </a>
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