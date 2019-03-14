<?php

namespace Drupal\les_plates\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'EditoBlock' block.
 *
 * @Block(
 *  id = "edito_block",
 *  admin_label = @Translation("Edito home"),
 * )
 */
class EditoBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
        "edito" => "",
          ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['edito'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Edito'),
      '#default_value' => $this->configuration['edito'],
      '#weight' => '0',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['edito'] = $form_state->getValue('edito');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['edito_block_edito']['#markup'] = '<p>' . $this->configuration['edito'] . '</p>';

    return $build;
  }

}
