<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
    <?php echo $poleTextu[1000] ?? 'Nenašel jsem text'; ?>
</title>
<link rel="stylesheet" href="css/styly.css?v=<?php echo @filemtime(__DIR__ . '/css/styly.css') ?: time(); ?>">
<script src="js/navigace.js?v=20251229-4" defer></script>