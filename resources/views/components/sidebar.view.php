<?php
$type = $type ?? 'guest';
$class = $class ?? '';
$slots = $slots ?? [];
?>
<div class="sidebar <?= $class ?>">
    <?php if(!empty($slots['header'])): ?>
        <div class="sidebar-header">
            <?= $slots['header'] ?>
        </div>
    <?php endif; ?>

    <div class="sidebar-section">
        <?php
        $menuItems = [];
        if($type === 'admin') {
            $menuItems = [
                ['name'=>'Dashboard','link'=>'/dashboard'],
                ['name'=>'Users','link'=>'/users'],
                ['name'=>'Settings','link'=>'/settings'],
            ];
        } elseif($type === 'editor') {
            $menuItems = [
                ['name'=>'Dashboard','link'=>'/dashboard'],
                ['name'=>'Posts','link'=>'/posts'],
            ];
        } else {
            $menuItems = [
                ['name'=>'Home','link'=>'/home'],
                ['name'=>'Profile','link'=>'/profile'],
            ];
        }

        $currentPage = $_SERVER['REQUEST_URI'] ?? '/';
        foreach($menuItems as $item): ?>
            <div id="tab" class="<?= $currentPage === $item['link'] ? 'active' : '' ?>">
                <a href="<?= $item['link'] ?>"><?= $item['name'] ?></a>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if(!empty($slots['footer'])): ?>
        <div class="sidebar-footer">
            <?= $slots['footer'] ?>
        </div>
    <?php endif; ?>
</div>

