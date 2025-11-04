<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Refresh tabel users dan muzakki dengan struktur lengkap
     */
    public function up(): void
    {
        // Drop foreign keys yang terkait dengan users dan muzakki
        $this->dropForeignKeys();

        // Drop tabel muzakki dulu (karena ada foreign key ke users)
        Schema::dropIfExists('muzakki');

        // Drop tabel users
        Schema::dropIfExists('users');

        // Recreate tabel users dengan struktur lengkap
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('role', ['admin', 'muzakki'])->default('muzakki');
            $table->boolean('is_active')->default(true);
            $table->string('phone', 20)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Recreate tabel muzakki dengan struktur lengkap
        Schema::create('muzakki', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('country', 255)->nullable();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->boolean('phone_verified')->default(false);
            $table->string('nik', 20)->unique()->nullable(); // NIK/KTP
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->text('address')->nullable();
            $table->string('province', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('district', 255)->nullable();
            $table->string('village', 255)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->decimal('monthly_income', 15, 2)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('campaign_url', 500)->nullable();
            $table->string('profile_photo', 500)->nullable();
            $table->string('ktp_photo', 500)->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // Recreate foreign keys yang terkait dengan users
        $this->recreateForeignKeys();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys
        $this->dropForeignKeys();

        // Drop tabel
        Schema::dropIfExists('muzakki');
        Schema::dropIfExists('users');

        // Recreate dengan struktur asli - ini akan di-handle oleh migration lain
        // Kita tidak perlu recreate di sini karena akan di-handle oleh rollback migration lainnya
    }

    /**
     * Drop semua foreign key yang terkait dengan users dan muzakki
     */
    private function dropForeignKeys(): void
    {
        // Drop foreign key dari zakat_payments ke muzakki
        try {
            Schema::table('zakat_payments', function (Blueprint $table) {
                $table->dropForeign(['muzakki_id']);
            });
        } catch (\Exception $e) {
            // Foreign key mungkin sudah tidak ada
        }

        // Drop foreign key dari zakat_payments ke users
        try {
            Schema::table('zakat_payments', function (Blueprint $table) {
                $table->dropForeign(['received_by']);
            });
        } catch (\Exception $e) {
            // Foreign key mungkin sudah tidak ada
        }

        // Drop foreign key dari mustahik ke users
        try {
            Schema::table('mustahik', function (Blueprint $table) {
                $table->dropForeign(['verified_by']);
            });
        } catch (\Exception $e) {
            // Foreign key mungkin sudah tidak ada
        }

        // Drop foreign key dari zakat_distributions ke users
        try {
            Schema::table('zakat_distributions', function (Blueprint $table) {
                $table->dropForeign(['distributed_by']);
            });
        } catch (\Exception $e) {
            // Foreign key mungkin sudah tidak ada
        }

        // Drop foreign key dari artikels ke users
        try {
            Schema::table('artikels', function (Blueprint $table) {
                $table->dropForeign(['author_id']);
            });
        } catch (\Exception $e) {
            // Foreign key mungkin sudah tidak ada
        }

        // Drop foreign key dari news ke users
        try {
            Schema::table('news', function (Blueprint $table) {
                $table->dropForeign(['news_author_id_foreign']);
            });
        } catch (\Exception $e) {
            try {
                Schema::table('news', function (Blueprint $table) {
                    $table->dropForeign(['author_id']);
                });
            } catch (\Exception $e2) {
                // Foreign key mungkin sudah tidak ada
            }
        }
    }

    /**
     * Recreate semua foreign key yang terkait dengan users
     */
    private function recreateForeignKeys(): void
    {
        // Recreate foreign key dari zakat_payments ke muzakki
        if (Schema::hasTable('zakat_payments')) {
            try {
                Schema::table('zakat_payments', function (Blueprint $table) {
                    $table->foreign('muzakki_id')->references('id')->on('muzakki')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Column mungkin tidak ada
            }
        }

        // Recreate foreign key dari zakat_payments ke users
        if (Schema::hasTable('zakat_payments')) {
            try {
                Schema::table('zakat_payments', function (Blueprint $table) {
                    $table->foreign('received_by')->references('id')->on('users')->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Column mungkin tidak ada
            }
        }

        // Recreate foreign key dari mustahik ke users
        if (Schema::hasTable('mustahik')) {
            try {
                Schema::table('mustahik', function (Blueprint $table) {
                    $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Column mungkin tidak ada
            }
        }

        // Recreate foreign key dari zakat_distributions ke users
        if (Schema::hasTable('zakat_distributions')) {
            try {
                Schema::table('zakat_distributions', function (Blueprint $table) {
                    $table->foreign('distributed_by')->references('id')->on('users')->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Column mungkin tidak ada
            }
        }

        // Recreate foreign key dari artikels ke users
        if (Schema::hasTable('artikels')) {
            try {
                Schema::table('artikels', function (Blueprint $table) {
                    $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Column mungkin tidak ada
            }
        }

        // Recreate foreign key dari news ke users
        if (Schema::hasTable('news')) {
            try {
                Schema::table('news', function (Blueprint $table) {
                    $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Column mungkin tidak ada
            }
        }
    }
};
