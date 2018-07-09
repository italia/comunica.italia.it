<?php

/**
 * @file
 * Implements preprocess functions for six8theme theme.
 */

/**
 * Implements template_preprocess_html().
 */
function six8theme_preprocess_html(&$variables) {
  global $base_url;
  if (isset($variables["node_type"])) {
    if ($node = \Drupal::request()->attributes->get('node')) {
      $variables['attributes']['class'][] = 'page-node-' . $node->id();
    }
  }
  $variables['base_url'] = $base_url;
}

/**
 * Implements template_preprocess_page.
 */
function six8theme_preprocess_page(&$variables) {
  global $base_url;
  $variables['base_url'] = $base_url;
}

/**
 * Implements template_preprocess_region.
 */
function six8theme_preprocess_region(&$variables) {
  global $base_url;
  $variables['base_url'] = $base_url;
}

/**
 * Implements template_preprocess_views_view.
 */
function six8theme_preprocess_views_view(&$variables) {
  $variables['attributes']['class'][] = 'view-id-' . $variables['view']->id();
  $variables['attributes']['class'][] = 'view-display-' . $variables['view']->current_display;
}

/**
 * Implements template_preprocess_block().
 */
function six8theme_preprocess_block(&$variables) {
  global $base_url;
  if (isset($variables['elements']['#id'])) {
    $variables['attributes']['class'][] = 'block-id-' . $variables['elements']['#id'];
  }
  $variables['base_url'] = $base_url;
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
