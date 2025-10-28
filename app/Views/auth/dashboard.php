<?= $this->extend('template') ?>

<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('admin/admin') ?>
<?= $this->endSection() ?>