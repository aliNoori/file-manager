<?php

namespace FileManager\Contracts;

use Illuminate\Http\Request;

interface FileManagerInterface
{
    public function handleRequestFiles(Request $request, string $context = null): array;
}

