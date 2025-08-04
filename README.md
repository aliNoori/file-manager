# 📂 FileManager

**FileManager** is a Laravel package for polymorphic file management.  
It provides a centralized API for uploading files and attaching them to any model, with support for multiple storage disks and dynamic model resolution.

---

## 📦 Installation

Install the package via Composer:

```bash
composer require amedev/file-manager

php artisan vendor:publish --tag=config
php artisan vendor:publish --tag=file-manager-migrations
php artisan vendor:publish --tag=file-manager-models

php artisan migrate

⚙️ Configuration
File: config/filemanager.php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Storage Disk
    |--------------------------------------------------------------------------
    | Supported options: 'local', 'public', 'sftp'
    */
    'disk' => env('FILE_MANAGER_DISK', 'sftp'),

    /*
    |--------------------------------------------------------------------------
    | Default Upload Path
    |--------------------------------------------------------------------------
    */
    'default_path' => 'uploads',

    /*
    |--------------------------------------------------------------------------
    | Model Map
    |--------------------------------------------------------------------------
    | Maps model aliases to actual class names.
    | Used for polymorphic file attachment.
    */
    'model_map' => [
        'user' => \App\Models\User::class,
        // Add more models as needed
    ],
];

🧪 API Usage
Upload Files

POST /filemanager/upload
Content-Type: multipart/form-data

fields:
- model_type: user
- model_id: 5
- files[]: (multiple files)



[
  {
    "id": 1,
    "path": "uploads/user/files/abc123.png",
    "mime_type": "image/png",
    "size": 31200,
    "field": "files"
  },
  ...
]

🧬 Polymorphic Relation
The File model uses a morphTo relation:

public function fileable()
{
    return $this->morphTo();
}


🛠 Artisan Commands
Fix Model Namespace

php artisan file-manager:fix-model-namespace

