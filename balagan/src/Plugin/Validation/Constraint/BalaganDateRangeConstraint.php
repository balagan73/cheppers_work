<?php

/**
 * @file
 * Contains \Drupal\balagan\Plugin\Validation\Constraint\BalaganDateRangeConstraint.
 */

namespace Drupal\balagan\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks if an entity date field has start time after end time, and end time is in the future.
 *
 * @Constraint(
 *   id = "BalaganDateRangeConstraint",
 *   label = @Translation("Date range constraint", context = "Validation"),
 * )
 */
class BalaganDateRangeConstraint extends Constraint {

  public $message = 'The end date has to be higher then the start date.';

  /**
   * {@inheritdoc}
   */
  public function validatedBy() {
    return get_class($this);
  }

  /**
   * @var \Symfony\Component\Validator\ExecutionContextInterface
   */
  protected $context;

  /**
   * {@inheritDoc}
   */
  public function initialize(ExecutionContextInterface $context) {
    $this->context = $context;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($items, Constraint $constraint) {
    if (isset($items)) {
      $date_values = $items->getValue();
      if ($date_values[0]['value'] >= $date_values[1]['value']) {
        $this->context->addViolation($constraint->message);
      }
    }
  }
}