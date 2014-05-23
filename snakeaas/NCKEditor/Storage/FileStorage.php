<?php
/**
 * @author  Martin "Snake.AAS" Grossmann <grossmann.martin@ovacloud.net>
 * @created 7.10.13 20:03
 */

namespace snakeaas\NCKEditor\Storage;

use Nette\Object;
use Nette\Utils\Finder;

class FileStorage extends Object implements IStorage {

	/** @var string */
	protected $uploadDirName;

	/** @var string */
	protected $wwwDir;

	/** @var string */
	protected $category = null;


	public function __construct($wwwDir, $uploadDir) {
		$this->uploadDirName = $uploadDir;
		$this->wwwDir        = realpath($wwwDir);
	}


	protected function getFSUploadDir() {
		$return = $this->wwwDir . DIRECTORY_SEPARATOR . $this->uploadDirName;

		if ($this->category) {
			$return .= DIRECTORY_SEPARATOR . $this->category;
		}

		if (!file_exists($return)) {
			mkdir($return, 0775, TRUE);
		}

		return $return;
	}


	/**
	 * Returns a base url for images
	 *
	 * @return string
	 */
	public function getBaseUrl() {
		$return = $this->uploadDirName;

		if ($this->category) {
			$return .= '/' . $this->category;
		}

		return $return;
	}


	/**
	 * Returns all files saved in storage
	 *
	 * @return mixed
	 */
	public function getFiles() {
		return Finder::findFiles('*')->in($this->getFSUploadDir());
	}

	/**
	 * Returns all files saved in storage
	 *
	 * @return mixed
	 */
	public function getImages() {
		return Finder::findFiles('*.jpg', '*.png', '*.gif')->in($this->getFSUploadDir());
	}

	/**
	 * Copy new file into storage
	 *
	 * @param string $file
	 * @param string $newFileName
	 *
	 * @return void
	 */
	public function addFile($file, $newFileName) {
		copy($file, $this->getFSUploadDir() . DIRECTORY_SEPARATOR . basename($newFileName));
	}


	/**
	 * Delete image from storage
	 *
	 * @param $fileName
	 *
	 * @return void
	 */
	public function deleteFile($fileName) {
		unlink($this->getFSUploadDir() . DIRECTORY_SEPARATOR . $fileName);
	}


	/**
	 * @param string $category
	 */
	public function setCategory($category) {
		$this->category = $category;
	}


	/**
	 * @return string
	 */
	public function getCategory() {
		return $this->category;
	}
}