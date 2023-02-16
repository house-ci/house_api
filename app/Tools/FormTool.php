<?php

namespace App\Tools;

class FormTool
{
    public static function generateForm($url, $dataInput, $name = 'redirect_form', $method = 'POST', $autoClick = true)
    {
        if (is_string($dataInput)) {
            parse_str($dataInput, $data);
        } else {
            $data = $dataInput;
        }
        $form = "<form name='{$name}' id='{$name}' method='{$method}' action='{$url}'>";
        foreach ($data as $k => $v) {
            $form .= '<input type="hidden" name="' . $k . '" value="' . $v . '">';
        }
        $form .= '</form>';
        if ($autoClick) {
            $form .= sprintf("<script>window.onload = function () {document.getElementById('%s').submit();}</script>", $name);
        }
        return $form;
    }

    public static function getReturnUrl($transaction, $service, $payment = null)
    {
        if (!empty($payment->return_url)) {
            $returnUrl = $payment->return_url;
        } elseif (!empty($transaction->return_url)) {
            $returnUrl = $transaction->return_url;
        } elseif (!empty(@$service->return_url)) {
            $returnUrl = $service->return_url;
        } elseif (!empty(@$service->cpm_return_url)) {
            $returnUrl = $service->cpm_return_url;
        } elseif (!empty(@$service->cpm_url_return)) {
            $returnUrl = $service->cpm_url_return;
        } else {
            $returnUrl = null;
        }
        return $returnUrl;
    }
}
