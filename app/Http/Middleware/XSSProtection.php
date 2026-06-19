<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XSSProtection
{
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        $this->cleanArray($input);

        $request->merge($input);

        return $next($request);
    }

    private function cleanArray(array &$input)
    {
        foreach ($input as $key => &$value) {
            
            if (in_array($key, ['password', 'password_confirmation', '_token'])) {
                continue;
            }

            if (is_array($value)) {
                $this->cleanArray($value);
            } else {
                $value = $this->sanitize($value);
            }
        }
    }

    private function sanitize($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        $allowedTags = '<span><div><p><br><b><strong><i><em><u><a><img><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><table><thead><tbody><tr><td><th>';
        $value = strip_tags($value, $allowedTags);

        $value = preg_replace('/[\x00-\x1F\x7F]/', '', $value);

        $value = str_ireplace(['javascript:', 'vbscript:', 'data:text/html'], '', $value);

        $value = preg_replace('/(\s)(on[a-z]+)(\s*=)/i', '$1no-$2$3', $value);

        return $value;
    }
}