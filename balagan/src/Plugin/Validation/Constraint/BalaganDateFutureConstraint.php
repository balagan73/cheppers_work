<?php

/**
 * @file
 * Contains \Drupal\balagan\Plugin\Validation\Constraint\BalaganDateFutureConstraint.
 */

namespace Drupal\balagan\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Checks if an entity date field is in the past.
 *
 * @Constraint(
 *   id = "BalaganDateFutureConstraint",
 *   label = @Translation("Date future constraint", context = "Validation"),
 * )
 */
class BalaganDateFutureConstraint extends Constraint implements ConstraintValidatorInterface {

  public $message = 'The dates cannot be in the past.';

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
        $now = date("Y-m-d");
        if (($date_values[0]['value'] < $now) || ($now > $date_values[1]['value'])) {
          $this->context->addViolation($constraint->message);
        }
    }
  }
}