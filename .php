<!-- migrate 1 -->
Modify tickets table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTicketsTableIdColumn extends Migration
{
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Drop the existing primary key
            $table->dropPrimary();
            
            // Modify the id column
            $table->bigIncrements('id')->change();
        });
    }

    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            // If you need to revert, you might want to set it back to how it was
            // This assumes the original was an unsigned integer
            $table->integer('id')->unsigned()->change();
            
            // Re-add the primary key
            $table->primary('id');
        });
    }
}

<!-- migrate 2 -->

'sto' => 'required|string|in:BJA,CWD,MJY,PDL,PNL,SOR,RJW,CCL,BTJ,CKW,CCL,CPT,CSA,GNH,LEM,RCK',