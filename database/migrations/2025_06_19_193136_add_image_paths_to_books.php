<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing books with image paths (only for available images)
        $imageMapping = [
            '9783608938041' => 'images/01_herr_der_ringe_die_gefaehrten.jpg',  // Der Herr der Ringe
            '9783453317840' => 'images/02_dune_der_wuestenplanet.jpg',  // Dune
            '9783551551672' => 'images/03_harry_potter_stein_der_weisen.jpg',  // Harry Potter
            '9783453528833' => 'images/04_foundation_der_psychohistoriker.jpg',  // Foundation
            '9783442267743' => 'images/05_das_lied_von_eis_und_feuer.jpg',  // Game of Thrones
            '9783548234106' => 'images/06_1984_george_orwell.jpg',  // 1984
            '9783458177630' => 'images/07_stolz_und_vorurteil.jpg',  // Stolz und Vorurteil
            '9783150090092' => 'images/08_die_verwandlung_kafka.jpg',  // Die Verwandlung
            '9783596901968' => 'images/09_der_grosse_gatsby.jpg',  // Der große Gatsby
            '9783596196265' => 'images/10_gone_girl_das_perfekte_opfer.jpg',  // Gone Girl
            '9783462034745' => 'images/11_der_schwarm_frank_schaetzing.jpg',  // Der Schwarm
            '9783453435735' => 'images/12_millennium_verblendung_stieg_larsson.jpg',  // Millennium
            '9780132350884' => 'images/13_clean_code_robert_martin.jpg',  // Clean Code
            '9783570552698' => 'images/14_sapiens_yuval_harari.jpg',  // Sapiens
            '9783442178582' => 'images/15_atomic_habits_james_clear.jpg',  // Atomic Habits
            '9783548372570' => 'images/16_kaenguru_chroniken_marc_uwe_kling.jpg',  // Känguru-Chroniken
            '9783899812251' => 'images/17_der_hundertjaehrige_jonas_jonasson.jpg',  // Der Hundertjährige
            // Note: Images 18-21 are missing, so these books will have no image_path:
            // '9783499256356' => 'images/18_tschick_wolfgang_herrndorf.jpg', // Tschick
            // '9783570101926' => 'images/19_steve_jobs_walter_isaacson.jpg', // Steve Jobs
            // '9783442314256' => 'images/20_becoming_michelle_obama.jpg', // Becoming
            // '9783442314263' => 'images/21_salt_fat_acid_heat_samin_nosrat.jpg', // Salt Fat Acid Heat
        ];

        foreach ($imageMapping as $isbn => $imagePath) {
            DB::table('books')
                ->where('isbn', $isbn)
                ->update(['image_path' => $imagePath]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('books')->update(['image_path' => null]);
    }
};
