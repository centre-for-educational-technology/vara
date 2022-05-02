<?php

namespace Drupal\tlu_h5p;

/**
 * Interface TagServiceInterface.
 *
 * @package Drupal\tagclouds
 */
interface TluH5pTagServiceInterface
{

  /**
   * Return an array of tags.
   *
   * Gets the information from the database, passes it along to
   * the weight builder and returns these weighted tags. Note that the tags are
   * unordered at this stage, hence they need ordering either by calling
   * sortTags() or by your own ordering data.
   *
   * NB! The only difference with getTags is that only tags from listed entities are considered.
   *
   * @param array $vids
   *   Vocabulary ids representing the vocabularies where you want the tags from.
   * @param int $steps
   *   (optional) The amount of tag-sizes you will be using. If you give "12"
   *   you still get six different "weights". Defaults to 6.
   * @param int $size
   *   (optional) The number of tags that will be returned. Default to 60.
   * @param string $display
   *   (optional) The type of display "style"=weighted,"count"=numbered display.
   *
   * @return array
   *   An <em>unordered</em> array with tags-objects, containing the attribute
   *   $tag->weight.
   */
  public function getListedTags(array $vids, $steps = 6, $size = 60, $display = NULL);

}
