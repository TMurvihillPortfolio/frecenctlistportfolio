<?php if (isset($result) && $result !== '') : ?>
    <p class="signatureBox signatureBox__warning"><?php echo $result; unset($result); ?></p>
<?php endif; ?>