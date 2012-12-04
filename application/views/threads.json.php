<?php header('Content-Type: application/json') ?>

<?php $threads = array() ?>

<?php if (isset($thread_result)): ?>
  <?php $acq_lookup = array(
    '0' => 'default',
    '1' => 'buddy',
    '2' => 'enemy',
  ) ?>

  <?php foreach($thread_result->result() as $row): ?>
    <?php $thread = array(
      'subject' => $row->subject,
      'created' => $row->created,
      'closed'  => '1' === $row->closed,
      'nsfw'    => '1' === $row->nsfw,
      'thread_id' => $row->thread_id,
      'user_id' => $row->user_id,
      'category' => $row->category,
      'author_name' => $row->author_name,
      'responder_name' => $row->responder_name,
      'response_created' => $row->response_created,
      'response_count' => $row->response_count,
      'acq' => isset($acq_lookup[$row->acq]) ? $acq_lookup[$row->acq] : $acq_lookup['0'],
    ) ?>

    <?php $threads[] = $thread ?>
  <?php endforeach ?>
<?php endif ?>

<?php echo json_encode(array(
  'title' => $title,
  'threads' => $threads,
)) ?>