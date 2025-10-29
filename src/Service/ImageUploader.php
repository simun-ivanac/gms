<?php

/**
 * Image Uploader.
 */

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Psr\Log\LoggerInterface;

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
	 */
	public function __construct(
		private string $targetDirectory,
		private SluggerInterface $slugger,
		private LoggerInterface $logger,
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
	public function upload(UploadedFile $imageFile): string
	{
		$originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
		$safeFilename = $this->slugger->slug(strtolower($originalFilename));
		$newFileName = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

		try {
			$imageFile->move($this->getTargetDirectory(), $newFileName);
		} catch (FileException $e) {
			$this->logger->error($e->getMessage());
			throw new FileException('Unable to process image file.');
		}

		return $newFileName;
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
