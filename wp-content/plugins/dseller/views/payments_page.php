<?php include('_header.php'); ?>

<h2>Платежи</h2>

<?php $payments = $this->get_payments();?>
<?php if ($payments): ?>
    <table class="table-bordered">
        <tr>
            <?php foreach($payments[0] as $key => $val): ?>
                <th> <?php echo $key; ?></th>
            <?php endforeach;?>
        </tr>
        <?php foreach($payments as $row): ?>
            <tr>
                <?php foreach($row as $key => $val): ?>
                    <td> <?php echo $val; ?> </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else:?>
    <p>Платежей нет</p>
<?php endif;?>

<?php include('_footer.php');?>

