<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GstTransaction extends Model {

    use HasFactory ;

    protected $table = "gst_transactions";
    protected $fillable = [ 'source' , 'source_id','source_no','person_type','person_id','person_name','person_contact','gst_type','gst','gst_amnt','igst','igst_amnt','cgst','cgst_amnt','sgst','sgst_amnt','base_amnt','amnt_status','action_taken','action_on','entry_by','role_id','shop_id','branch_id'] ;

    

}
