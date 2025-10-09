 <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('user_queries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('name'); // Esta columna es la que falta en tu BD
                $table->string('type');
                $table->string('value_type');
                $table->string('period');
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('user_queries');
        }
    };
