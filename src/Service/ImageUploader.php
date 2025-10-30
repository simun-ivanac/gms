<?php

/**
 * Image Uploader.
 */

declare(strict_types=1);

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class ImageUploader.
 */
class ImageUploader
{
	/**
	 * ImageUploader constructor.
	 *
	 * @param string            $targetDirectory Target directory.
	 * @param SluggerInterface  $slugger         Slugger.
	 * @param LoggerInterface   $logger          Logger.
	 * @param Filesystem        $filesystem      Filesystem.
	 */
	public function __construct(
		private string $targetDirectory,
		private SluggerInterface $slugger,
		private LoggerInterface $logger,
		private Filesystem $filesystem,
	) {
	}

	/**
	 * Upload image.
	 *
	 * @param UploadedFile $file File to upload.
	 *
	 * @throws FileException If something goes wrong with processing image.
	 *
	 * @return string
	 */
	public function uploadFile(UploadedFile $newImageFile): string
	{
		$originalFilename = pathinfo($newImageFile->getClientOriginalName(), PATHINFO_FILENAME);
		$safeFilename = $this->slugger->slug(strtolower($originalFilename));
		$newFileName = $safeFilename . '-' . uniqid() . '.' . $newImageFile->guessExtension();

		try {
			$newImageFile->move($this->getTargetDirectory(), $newFileName);
		} catch (FileException $e) {
			$this->logger->error($e->getMessage());
			throw new FileException('Unable to upload image file.');
		}

		return $newFileName;
	}

	/**
	 * Delete image.
	 *
	 * @param string $oldImageFile Old image file.
	 *
	 * @return bool
	 */
	public function deleteFile(string $oldImageFile)
	{
		if (!$oldImageFile) {
			return false;
		}

		// Log error if it fails, but continue anyway.
		try {
			$this->filesystem->remove($this->getTargetFile($oldImageFile));
		} catch (FileException $e) {
			$this->logger->error($e->getMessage());
			return false;
		}

		return true;
	}

	/**
	 * Get full target path.
	 *
	 * @param string $filename File name.
	 *
	 * @return string
	 */
	public function getTargetFile(string $filename): string
	{
		return $this->getTargetDirectory() . DIRECTORY_SEPARATOR . $filename;
	}

	/**
	 * Get target directory.
	 *
	 * @return string
	 */
	public function getTargetDirectory(): string
	{
		return $this->targetDirectory;
	}
}
