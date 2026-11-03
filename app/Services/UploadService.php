<?php

namespace App\Services;

use App\Repositories\Sql\SqlUploadRepository;

class UploadService extends BaseService
{
    protected SqlUploadRepository $UploadRepository;

    public function __construct(SqlUploadRepository $UploadRepository)
    {
        parent::__construct($UploadRepository);
        $this->UploadRepository = $UploadRepository;
    }

    public function findModuleCode(): string
    {
        return 'upload';
    }

    public function findModuleName(): string
    {
        return 'Uploads';
    }

    /**
     * Upload a file according to MUploadSetup configuration.
     * Generates a temporary file prefixed with "tmp_{uuid}".
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string|null $moduleId
     * @param string $name
     * @return array
     */
    public function uploadFile(\Illuminate\Http\UploadedFile $file, ?string $moduleId, string $name): array
    {
        $setupService = app(\App\Services\UploadSetupService::class);
        $setupRes = $setupService->findByModuleIdByName($moduleId, $name);
        if (!$setupRes['success']) {
            return $this->formatResponse(false, null, 'Upload configuration not found for ' . $name);
        }

        $setup = $setupRes['data'];

        // Validate max size (max_size is in KB in m_upload_setup)
        $maxSizeBytes = ($setup->max_size ?: 10240) * 1024;
        if ($file->getSize() > $maxSizeBytes) {
            return $this->formatResponse(false, null, 'File size exceeds maximum allowed size (' . ($setup->max_size) . ' KB).');
        }

        // Validate extension / mime
        $ext = strtolower($file->getClientOriginalExtension());
        if (!empty($setup->allowed_files)) {
            $allowed = array_map('trim', explode(',', strtolower($setup->allowed_files)));
            if (!in_array($ext, $allowed, true)) {
                return $this->formatResponse(false, null, 'File type .' . $ext . ' is not allowed. Allowed: ' . $setup->allowed_files);
            }
        }

        $uuid = (string) \Illuminate\Support\Str::uuid();
        $tmpFileName = 'tmp_' . $uuid . '.' . $ext;
        $targetDir = trim($setup->path ?: 'assets/images/user', '/');

        // Check if external storage (S3) or local public
        $isExternal = (bool) $setup->is_external_storage && !empty(config('filesystems.disks.s3.key'));

        $fullPath = $targetDir . '/' . $tmpFileName;

        if ($isExternal) {
            \Illuminate\Support\Facades\Storage::disk('s3')->putFileAs($targetDir, $file, $tmpFileName, 'public');
            $fileUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($fullPath);
        } else {
            $destinationPath = public_path($targetDir);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $tmpFileName);

            // Perform server-side resizing if configured and not already cropped
            if ($setup->is_resized && $setup->pic_width > 0 && $setup->pic_height > 0) {
                $this->resizeImageFile($destinationPath . '/' . $tmpFileName, $ext, $setup->pic_width, $setup->pic_height);
            }

            $fileUrl = asset($fullPath);
        }

        // Record in t_upload with is_temporary = 1
        $uploadRecord = $this->UploadRepository->create([
            'id' => $uuid,
            'm_upload_setup_id' => $setup->id,
            'm_module_id' => $setup->m_module_id,
            'name' => $name,
            'path' => $fullPath,
            'type' => $ext,
            'file_source' => $fileUrl,
            'is_external_storage' => $isExternal ? 1 : 0,
            'is_temporary' => 1,
            'is_active' => 1,
            'created_by' => auth()->id(),
        ]);

        return $this->formatResponse(true, [
            'file_id' => $uploadRecord->id,
            'file_name' => $tmpFileName,
            'file_source' => $fileUrl,
            'url' => $fileUrl,
            'file_extension' => $ext,
            'path' => $fullPath,
        ], 'File uploaded successfully.');
    }

    /**
     * Upload an image from base64 data (e.g. from Cropper.js or compressed canvas).
     *
     * @param string $base64Data
     * @param string|null $moduleId
     * @param string $name
     * @return array
     */
    public function uploadCroppedBase64(string $base64Data, ?string $moduleId, string $name): array
    {
        $setupService = app(\App\Services\UploadSetupService::class);
        $setupRes = $setupService->findByModuleIdByName($moduleId, $name);
        if (!$setupRes['success']) {
            return $this->formatResponse(false, null, 'Upload configuration not found for ' . $name);
        }

        $setup = $setupRes['data'];

        // Extract mime and decode base64
        $ext = 'jpg';
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
            $ext = strtolower($type[1]);
            if ($ext === 'jpeg') $ext = 'jpg';
        }
        $decodedData = base64_decode($base64Data);
        if ($decodedData === false) {
            return $this->formatResponse(false, null, 'Invalid base64 image data.');
        }

        $uuid = (string) \Illuminate\Support\Str::uuid();
        $tmpFileName = 'tmp_' . $uuid . '.' . $ext;
        $targetDir = trim($setup->path ?: 'assets/images/user', '/');

        $isExternal = (bool) $setup->is_external_storage && !empty(config('filesystems.disks.s3.key'));
        $fullPath = $targetDir . '/' . $tmpFileName;

        if ($isExternal) {
            \Illuminate\Support\Facades\Storage::disk('s3')->put($fullPath, $decodedData, 'public');
            $fileUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($fullPath);
        } else {
            $destinationPath = public_path($targetDir);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $targetFilePath = $destinationPath . '/' . $tmpFileName;
            file_put_contents($targetFilePath, $decodedData);

            if ($setup->is_resized && $setup->pic_width > 0 && $setup->pic_height > 0) {
                $this->resizeImageFile($targetFilePath, $ext, $setup->pic_width, $setup->pic_height);
            }

            $fileUrl = asset($fullPath);
        }

        $uploadRecord = $this->UploadRepository->create([
            'id' => $uuid,
            'm_upload_setup_id' => $setup->id,
            'm_module_id' => $setup->m_module_id,
            'name' => $name,
            'path' => $fullPath,
            'type' => $ext,
            'file_source' => $fileUrl,
            'is_external_storage' => $isExternal ? 1 : 0,
            'is_temporary' => 1,
            'is_active' => 1,
            'created_by' => auth()->id(),
        ]);

        return $this->formatResponse(true, [
            'file_id' => $uploadRecord->id,
            'file_name' => $tmpFileName,
            'file_source' => $fileUrl,
            'url' => $fileUrl,
            'file_extension' => $ext,
            'path' => $fullPath,
        ], 'Image uploaded successfully.');
    }

    /**
     * Finalize uploaded file: removes 'tmp_' prefix and sets is_temporary = 0.
     * Returns permanent relative path or URL for saving into entity column (e.g. m_user.image_src).
     *
     * @param string|null $pathOrUrlOrId
     * @return string|null Permanent relative path or source URL
     */
    public function finalizeUpload(?string $pathOrUrlOrId): ?string
    {
        if (empty($pathOrUrlOrId)) {
            return null;
        }

        // If it's not a temporary file, return as is
        if (!str_contains($pathOrUrlOrId, 'tmp_')) {
            return $pathOrUrlOrId;
        }

        // Find record by file_source, path, or id
        $upload = $this->UploadRepository->findByFileSource($pathOrUrlOrId);
        if (!$upload) {
            $upload = $this->UploadRepository->findByPath($pathOrUrlOrId);
        }
        if (!$upload) {
            $upload = $this->UploadRepository->find($pathOrUrlOrId);
        }

        if ($upload) {
            $oldPath = $upload->path;
            $newPath = str_replace('tmp_', '', $oldPath);

            if ($upload->is_external_storage) {
                if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($oldPath)) {
                    \Illuminate\Support\Facades\Storage::disk('s3')->move($oldPath, $newPath);
                }
                $newFileSource = \Illuminate\Support\Facades\Storage::disk('s3')->url($newPath);
            } else {
                $oldPublicFile = public_path($oldPath);
                $newPublicFile = public_path($newPath);

                if (file_exists($oldPublicFile)) {
                    @rename($oldPublicFile, $newPublicFile);
                }
                $newFileSource = asset($newPath);
            }

            $upload->update([
                'path' => $newPath,
                'file_source' => $newFileSource,
                'is_temporary' => 0,
            ]);

            return $upload->is_external_storage ? $newFileSource : $newPath;
        }

        // If no DB record was found but string contains tmp_ in public file system
        $cleanPath = ltrim(parse_url($pathOrUrlOrId, PHP_URL_PATH), '/');
        if (str_contains($cleanPath, 'tmp_')) {
            $newCleanPath = str_replace('tmp_', '', $cleanPath);
            if (file_exists(public_path($cleanPath))) {
                @rename(public_path($cleanPath), public_path($newCleanPath));
            }
            return $newCleanPath;
        }

        return $pathOrUrlOrId;
    }

    /**
     * Helper to resize image using PHP GD.
     *
     * @param string $filePath
     * @param string $ext
     * @param int $targetWidth
     * @param int $targetHeight
     */
    private function resizeImageFile(string $filePath, string $ext, int $targetWidth, int $targetHeight): void
    {
        if (!file_exists($filePath) || !extension_loaded('gd')) {
            return;
        }

        $image = null;
        switch (strtolower($ext)) {
            case 'jpg':
            case 'jpeg':
                $image = @imagecreatefromjpeg($filePath);
                break;
            case 'png':
                $image = @imagecreatefrompng($filePath);
                break;
            case 'webp':
                $image = @imagecreatefromwebp($filePath);
                break;
            case 'gif':
                $image = @imagecreatefromgif($filePath);
                break;
        }

        if (!$image) return;

        $origWidth = imagesx($image);
        $origHeight = imagesy($image);

        // Aspect ratio calculation
        $ratio = min($targetWidth / $origWidth, $targetHeight / $origHeight);
        $newWidth = (int) round($origWidth * $ratio);
        $newHeight = (int) round($origHeight * $ratio);

        $resized = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG and WebP
        if (in_array(strtolower($ext), ['png', 'webp', 'gif'], true)) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
            imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        switch (strtolower($ext)) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($resized, $filePath, 85);
                break;
            case 'png':
                imagepng($resized, $filePath, 8);
                break;
            case 'webp':
                imagewebp($resized, $filePath, 85);
                break;
            case 'gif':
                imagegif($resized, $filePath);
                break;
        }

        imagedestroy($image);
        imagedestroy($resized);
    }
}