<?= $this->extend('template') ?>

<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div style="padding: 40px; text-align: center;">
    <h1>Welcome to the Dashboard!</h1>
    <p>You are successfully logged in.</p>
    <a href="<?= base_url('logout') ?>" style="background-color: #011936; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Logout</a>
</div>
<?= $this->endSection() ?>