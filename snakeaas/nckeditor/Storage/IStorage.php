<?php
/**
 * @author  Martin "Snake.AAS" Grossmann <grossmann.martin@ovacloud.net>
 * @created 7.10.13 20:02
 */

namespace snakeaas\NCKEditor\Storage;

interface IStorage {

	/**
	 * Returns all files saved in storage
	 *
	 * @return mixed
	 */
	public function getFiles();


	/**
	 * Return all images saved in storage
	 *
	 * @return mixed
	 */
	public function getImages();


	/**
	 * Copy new file into storage
	 *
	 * @param string $file
	 * @param string $newFileName
	 *
	 * @return void
	 */
	public function addFile($file, $newFileName);


	/**
	 * Delete image from storage
	 *
	 * @param $fileName
	 *
	 * @return void
	 */
	public function deleteFile($fileName);


	/**
	 * Returns a base url for images
	 *
	 * @return string
	 */
	public function getBaseUrl();


	/**
	 * @param string $category
	 */
	public function setCategory($category);


	/**
	 * @return string
	 */
	public function getCategory();
}