<?php

namespace Drupal\tlu_h5p\Commands;

use Drush\Commands\DrushCommands;


class TluH5pCommands extends DrushCommands
{
  /**
   * Reassign all content from one account to the other.
   *
   * @command tlu-h5p:reassign
   *
   * @param string $from Username to reassign content from
   * @param string $to   Username to reassign content to
   * @aliases tlu-h5p-reassign
   * @usage tlu-h5p:reassign username1 username2
   *   Reassign all the content created by username1 and assign that to username2
   */
  public function reassign(string $from, string $to) {
    $fromAccount = user_load_by_name($from);
    $toAccount = user_load_by_name($to);

    if (!$fromAccount) {
      throw new \Exception(dt('User account with name of !name does not exist!', ['!name' => $from]));
    }

    if (!$toAccount) {
      throw new \Exception(dt('User account with name of !name does not exist!', ['!name' => $to]));
    }

    if ($this->io()->confirm(dt('Reassign all content from account !from to account !to? ', [
      '!from' => $fromAccount->getAccountName(),
      '!to' => $toAccount->getAccountName(),
    ]))) {
      // Reassign nodes (current revisions).
      module_load_include('inc', 'node', 'node.admin');
      $nodes = \Drupal::entityQuery('node')
        ->condition('uid', $fromAccount->id())
        ->execute();
      node_mass_update($nodes, [
        'uid' => $toAccount->id(),
      ], NULL, TRUE);

      // Reassign old revisions.
      \Drupal::database()
        ->update('node_field_revision')
        ->fields([
          'uid' => $toAccount->id(),
        ])
        ->condition('uid', $fromAccount->id())
        ->execute();

      if ($nodes && is_array($nodes) && count($nodes) > 0) {
        $this->logger->success(dt('Reassigned !count content from account !from to account !to. Also dealt with any old revisions.', [
          '!count' => count($nodes),
          '!from' => $fromAccount->getAccountName(),
          '!to' => $toAccount->getAccountName(),
        ]));
      }
    }
  }
}
