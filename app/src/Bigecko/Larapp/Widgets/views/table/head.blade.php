<thead>
  <tr>
  <?php foreach ($vars as $var): ?>
    <?php if (is_string($var)): ?>
    <th>{{{ $var }}}</th>
    <?php else: ?>
    <th class="{{ $var['field'] }} {{ $var['active_sort'] }}">
      <a href="{{ $var['url'] }}">{{ $var['text'] }}</a>
    </th>
    <?php endif; ?>
  <?php endforeach; ?>
  </tr>
</thead>
