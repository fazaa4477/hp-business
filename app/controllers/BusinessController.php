<?php
declare(strict_types=1);

class BusinessController
{
    private Business $business;
    public function __construct(PDO $pdo) { $this->business=new Business($pdo); }
    public function page(string $page): void { require_auth(); $data=['pageTitle'=>ucfirst($page).' — HP Business','page'=>$page,'flash'=>flash(),'products'=>$this->business->products(),'suppliers'=>$this->business->suppliers(),'customers'=>$this->business->customers(),'units'=>$this->business->units($_GET['q']??''),'availableUnits'=>$this->business->availableUnits(),'report'=>$this->business->report()]; render('app/page',$data); }
    public function saveMaster(): void { require_auth(); verify_csrf(); try { $this->business->create($_POST['type']??'', $_POST); flash('Data berhasil ditambahkan.'); } catch(Throwable $e) { flash('Gagal menyimpan: '.$e->getMessage()); } redirect($_POST['return']??'products'); }
    public function purchase(): void { require_auth(); verify_csrf(); try { $this->business->purchase($_POST); flash('Pembelian dan unit HP berhasil dicatat.'); } catch(Throwable $e) { flash('Gagal mencatat pembelian: '.$e->getMessage()); } redirect('purchases'); }
    public function sale(): void { require_auth(); verify_csrf(); try { $this->business->sale($_POST); flash('Penjualan berhasil dicatat dan stok diperbarui.'); } catch(Throwable $e) { flash('Gagal mencatat penjualan: '.$e->getMessage()); } redirect('sales'); }
    public function products():void{$this->page('products');} public function suppliers():void{$this->page('suppliers');} public function customers():void{$this->page('customers');} public function inventory():void{$this->page('inventory');} public function purchases():void{$this->page('purchases');} public function sales():void{$this->page('sales');} public function reports():void{$this->page('reports');}
}
