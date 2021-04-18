
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class <?=$classname?> extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('<?=$table_name?>', function (Blueprint $table) {
            $table-><?=$schema?>;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('<?=$table_name?>', function (Blueprint $table) {
            $table->dropColumn('<?=$column_name?>');
        });
    }
}

