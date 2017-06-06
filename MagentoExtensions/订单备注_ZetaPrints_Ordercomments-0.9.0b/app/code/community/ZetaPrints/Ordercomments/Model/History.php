<?php
/**
 * Override default history model
 *
 * We need to know if a comment comes from customer,
 * this is simplest way to mark it.
 * A better way would be to wrap comment in an html tag
 * and use classes to separate it.
 * This however does not work because all comment content
 * is escaped before rendering, so we will end up with
 * html markup displayed to user.
 */
class ZetaPrints_Ordercomments_Model_History
  extends Mage_Sales_Model_Order_Status_History
{
  protected $customerCommentTpl = '%s...customer';

  public function getComment()
  {
    $comment = $this->nl2br(parent::getComment()); // use internal nl2br to avoid breaking old comments,
    // probably should remove it

    $id = $this->getId();
    $customerComment = Mage::getModel('ordercomments/comment')->load($id, 'comment_id');
    if ($customerComment->getId()) {
      $comment = sprintf($this->customerCommentTpl, $comment);
    }
    return $comment;
  }

  public function save()
  {
    $comment = parent::getComment();
    $this->setData('comment', $this->nl2br($comment));
    return parent::save();
  }

  protected function nl2br($text) {
    $lines = explode("\n", $text);
    $c = count($lines);
    foreach ($lines as $i => $line) {
      if (!preg_match('/<br\s*?\/?>/', $line) && ($i + 1) < $c) { // no br found
        $lines[$i] = trim($line) . '<br/>';
      }
    }
    $text = implode("\n", $lines);
    return $text;
  }
}
