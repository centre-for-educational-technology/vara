<?php

namespace Drupal\tlu_h5p\Plugin\Field\FieldFormatter;


use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceLabelFormatter;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'material tag' formatter.
 *
 * @FieldFormatter(
 *   id = "material_tag",
 *   label = @Translation("All materials tag filter"),
 *   description = @Translation("Display link to All Materials view with tag context filter applied."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */

class MaterialTagFormatter extends EntityReferenceLabelFormatter
{
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $output_as_link = $this->getSetting('link');

    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {
      $label = $entity->label();
      // If the link is to be displayed and the entity has a uri, display a
      // link.
      if ($output_as_link && !$entity->isNew()) {
        try {
          $uri = Url::fromRoute('view.all_materials.all_materials', [], [
            'query' => [
              'tag' => $entity->id(),
            ],
          ]);
        }
        catch (\InvalidArgumentException $e) {
          $output_as_link = FALSE;
        }
      }

      if ($output_as_link && isset($uri) && !$entity->isNew()) {
        $elements[$delta] = [
          '#type' => 'link',
          '#title' => $label,
          '#url' => $uri,
          '#options' => $uri->getOptions(),
        ];

        if (!empty($items[$delta]->_attributes)) {
          $elements[$delta]['#options'] += ['attributes' => []];
          $elements[$delta]['#options']['attributes'] += $items[$delta]->_attributes;
          // Unset field item attributes since they have been included in the
          // formatter output and shouldn't be rendered in the field template.
          unset($items[$delta]->_attributes);
        }
      }
      else {
        $elements[$delta] = ['#plain_text' => $label];
      }
      $elements[$delta]['#cache']['tags'] = $entity->getCacheTags();
    }

    return $elements;
  }
}
