<?php

/**
 * @file
 * Implements preprocess functions for six8theme theme.
 */

/**
 * Implements template_preprocess_html().
 */
function six8theme_preprocess_html(&$variables) {
  if (isset($variables["node_type"])) {
    if ($node = \Drupal::request()->attributes->get('node')) {
      $variables['attributes']['class'][] = 'page-node-' . $node->id();
    }
  }
  $variables['html_attributes']['lang'] = 'it';
}

/**
 * Implements template_preprocess_region.
 */
function six8theme_preprocess_region(&$variables) {
  $variables['header_site_title'] = theme_get_setting('site_title', 'six8theme');
  $variables['header_site_sottotitolo'] = theme_get_setting('site_sottotitolo', 'six8theme');
  $variables['header_site_occhiello'] = theme_get_setting('site_occhiello', 'six8theme');

  // Link for call to action
  $variables['cta_link_1'] = theme_get_setting('cta_link_1', 'six8theme');
  $variables['cta_link_2'] = theme_get_setting('cta_link_2', 'six8theme');

  // Button text for call to action
  $variables['cta_text_1'] = theme_get_setting('cta_text_1', 'six8theme');
  $variables['cta_text_2'] = theme_get_setting('cta_text_2', 'six8theme');

  // An exception might be thrown.
  try {
    $variables['is_front'] = \Drupal::service('path.matcher')->isFrontPage();
  } catch (Exception $e) {
    // If the database is not yet available, set the default value.
    $variables['is_front'] = FALSE;
  }
}

/**
 * Implements template_preprocess_views_view.
 */
function six8theme_preprocess_views_view(&$variables, &$suggestions) {
  $variables['attributes']['class'][] = 'view-id-' . $variables['view']->id();
  $variables['attributes']['class'][] = 'view-display-' . $variables['view']->current_display;
}

/**
 * Implements template_preprocess_block().
 */
function six8theme_preprocess_block(&$variables) {
  if (isset($variables['elements']['#id'])) {
    $variables['attributes']['class'][] = 'block-id-' . $variables['elements']['#id'];
  }
}

/**
 * Implements template_preprocess_node.
 */
function six8theme_preprocess_node(&$variables) {
  six8theme_node_base_classes($variables);
}

/**
 * Implements template_preprocess_layout__onecol().
 */
function six8theme_preprocess_layout__onecol(&$variables) {
  six8theme_node_base_classes($variables);
}

/**
 * Implements template_preprocess_field().
 */
function six8theme_preprocess_field(&$variables, $hook) {
  if (isset($variables['element']['#field_type'])) {
    $variables['attributes']['class'][] = 'field-type-' . $variables['element']['#field_type'];
  }

  if (isset($variables['element']['#field_name'])) {
    $variables['attributes']['class'][] = 'field-name-' . $variables['element']['#field_name'];
  }

  if (isset($variables['element']['#view_mode'])) {
    $variables['attributes']['class'][] = 'view-mode-' . $variables['element']['#view_mode'];
  }
}

/**
 * Implements template_preprocess_menu__main().
 */
function six8theme_preprocess_menu__main(&$variables) {

}

/**
 * Implements template_preprocess_breadcrumb().
 */
function six8theme_preprocess_breadcrumb(&$variables) {

}

/**
 * Set base classes for nodes.
 *
 * @param array $variables
 *   The $variables array.
 */
function six8theme_node_base_classes(array &$variables) {
  if (isset($variables['content']['#entity_type'])) {
    $classes[] = 'entity-type-' . $variables['content']['#entity_type'];
  }

  if (isset($variables['content']['#bundle'])) {
    $classes[] = 'entity-bundle-' . $variables['content']['#bundle'];
  }

  if (isset($variables['content']['#entity'])) {
    $classes[] = $variables['content']['#entity_type'] . '-' . $variables['content']['#entity']->ID();
  }

  if (!empty($classes)) {
    if (!empty($variables['attributes']['class'])) {
      $variables['attributes']['class'] = array_merge($variables['attributes']['class'], $classes);
    }
    else {
      $variables['attributes']['class'] = $classes;
    }
  }
}

/**
 * Implement hook_form_system_theme_settings_alter().
 *
 * @param $form
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 */

function six8theme_form_system_theme_settings_alter(&$form, Drupal\Core\Form\FormStateInterface $form_state) {
  $form['six8theme_settings'] = [
    '#type' => 'details',
    '#title' => t('Comunica Italia Themes Configuration'),
    '#description' => t('Configuration header themes'),
    '#weight' => -1000,
    '#open' => TRUE,
  ];

  $form['six8theme_settings']['header']['site_title'] = [
    '#type' => 'textfield',
    '#title' => t('Titolo del sito'),
    '#description' => 'Edit ' . theme_get_setting('site_title', 'six8theme'),
    '#default_value' => theme_get_setting('site_title', 'six8theme'),
  ];

  $form['six8theme_settings']['header']['site_sottotitolo'] = [
    '#type' => 'textfield',
    '#title' => t('Sottotitolo'),
    '#description' => 'Edit ' . theme_get_setting('site_sottotitolo', 'six8theme'),
    '#default_value' => theme_get_setting('site_sottotitolo', 'six8theme'),
  ];

  $form['six8theme_settings']['header']['site_occhiello'] = [
    '#type' => 'textarea',
    '#title' => t('Occhiello'),
    '#description' => 'Edit ' . theme_get_setting('site_occhiello', 'six8theme'),
    '#default_value' => theme_get_setting('site_occhiello', 'six8theme'),
  ];

  $form['six8theme_settings']['cta_header']['cta_link_1'] = [
    '#type' => 'entity_autocomplete',
    '#title' => t('Bottone #1 Link'),
    '#default_value' => \Drupal\node\Entity\Node::load(theme_get_setting('cta_link_1', 'six8theme')),
    '#target_type' => 'node',
    '#selection_handler' => 'default',
    '#selection_settings' => [
      'target_bundles' => ['page'],
    ],
  ];

  $form['six8theme_settings']['cta_header']['cta_text_1'] = [
    '#type' => 'textfield',
    '#title' => t('Bottone #1 Testo'),
    '#default_value' => theme_get_setting('cta_text_1', 'six8theme'),
  ];

  $form['six8theme_settings']['cta_header']['cta_link_2'] = [
    '#type' => 'entity_autocomplete',
    '#title' => t('Bottone #2 Link'),
    '#default_value' => \Drupal\node\Entity\Node::load(theme_get_setting('cta_link_2', 'six8theme')),
    '#target_type' => 'node',
    '#selection_handler' => 'default',
    '#selection_settings' => [
      'target_bundles' => ['page'],
    ],
  ];

  $form['six8theme_settings']['cta_header']['cta_text_2'] = [
    '#type' => 'textfield',
    '#title' => t('Bottone #2 Testo'),
    '#default_value' => theme_get_setting('cta_text_2', 'six8theme'),
  ];
}