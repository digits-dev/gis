<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSodVsEodVarianceReportView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW sod_vs_eod_variance_report AS
               WITH EndDates AS (
                    SELECT 
                        t2.locations_id,
                        t2.entry_date AS end_date,
                        t2.float_types_id,
                        t2.token_bal AS Token_Bal_EOD,  
                        t2.token_drawer AS Token_Drawer_EOD,  
                        t2.token_sealed AS Token_Sealed_EOD,                       
                        t2.created_by AS Created_by_EOD,  
                        t2.created_at AS Created_at_EOD,  
                        ROW_NUMBER() OVER (PARTITION BY t2.locations_id ORDER BY t2.entry_date DESC) AS rn
                    FROM 
                        cash_float_histories AS t2
                    WHERE 
                        t2.float_types_id = 2  -- EOD records
                )
                SELECT 
                    t1.locations_id,
                    loc.location_name,  -- Joining the locations table to get location_name
                    t1.entry_date AS start_date,
                    t1.token_bal AS Token_Bal_SOD,  -- Adding token_bal for SOD
                    t1.token_drawer AS Token_Drawer_SOD,  -- Adding token_drawer for SOD
                    t1.token_sealed AS Token_Sealed_SOD,  -- Adding token_sealed for SOD
                    t1.created_by AS Created_by_SOD,  -- Adding created_by for SOD
                    t1.created_at AS Created_at_SOD,  -- Adding created_at for SOD
                    -- Get the most recent EOD for the same location before the start_date (strictly before)
                    COALESCE(
                        (SELECT end_date 
                        FROM EndDates ed 
                        WHERE ed.locations_id = t1.locations_id
                        AND ed.end_date < t1.entry_date  -- Ensure the EOD is strictly before the start_date
                        ORDER BY ed.end_date DESC  -- Get the most recent one
                        LIMIT 1), NULL
                    ) AS end_date,
                    COALESCE(
                        (SELECT Token_Bal_EOD
                        FROM EndDates ed
                        WHERE ed.locations_id = t1.locations_id
                        AND ed.end_date < t1.entry_date  -- Get Token_Bal for the most recent EOD before the start_date
                        ORDER BY ed.end_date DESC
                        LIMIT 1), NULL
                    ) AS Token_Bal_EOD,  -- Adding EOD token_bal from subquery
                    COALESCE(
                        (SELECT Token_Drawer_EOD
                        FROM EndDates ed
                        WHERE ed.locations_id = t1.locations_id
                        AND ed.end_date < t1.entry_date  -- Get Token_Drawer for the most recent EOD before the start_date
                        ORDER BY ed.end_date DESC
                        LIMIT 1), NULL
                    ) AS Token_Drawer_EOD,  -- Adding EOD token_drawer from subquery
                    COALESCE(
                        (SELECT Token_Sealed_EOD
                        FROM EndDates ed
                        WHERE ed.locations_id = t1.locations_id
                        AND ed.end_date < t1.entry_date  -- Get Token_Sealed for the most recent EOD before the start_date
                        ORDER BY ed.end_date DESC
                        LIMIT 1), NULL
                    ) AS Token_Sealed_EOD,  -- Adding EOD token_sealed from subquery
                    COALESCE(
                        (SELECT Created_by_EOD
                        FROM EndDates ed
                        WHERE ed.locations_id = t1.locations_id
                        AND ed.end_date < t1.entry_date  -- Get Created_by for the most recent EOD before the start_date
                        ORDER BY ed.end_date DESC
                        LIMIT 1), NULL
                    ) AS Created_by_EOD,  -- Adding EOD created_by from subquery
                    COALESCE(
                        (SELECT Created_at_EOD
                        FROM EndDates ed
                        WHERE ed.locations_id = t1.locations_id
                        AND ed.end_date < t1.entry_date  -- Get Created_at for the most recent EOD before the start_date
                        ORDER BY ed.end_date DESC
                        LIMIT 1), NULL
                    ) AS Created_at_EOD,  -- Adding EOD created_at from subquery
                    u1.name AS Created_by_SOD_Name,  -- Getting the user name for SOD
                    u2.name AS Created_by_EOD_Name,  -- Getting the user name for EOD
                    
                    -- Calculate the Total SOD (Token_Drawer_SOD + Token_Sealed_SOD)
                    (t1.token_drawer + t1.token_sealed) AS Total_SOD,  
                    
                    -- Calculate the Total EOD (Token_Drawer_EOD + Token_Sealed_EOD)
                    COALESCE(
                        (SELECT Token_Drawer_EOD + Token_Sealed_EOD
                        FROM EndDates ed
                        WHERE ed.locations_id = t1.locations_id
                        AND ed.end_date < t1.entry_date  -- Get Total EOD for the most recent EOD before the start_date
                        ORDER BY ed.end_date DESC
                        LIMIT 1), NULL
                    ) AS Total_EOD,

                    -- Calculate the Variance (Total_SOD - Total_EOD)
                    (t1.token_drawer + t1.token_sealed) - COALESCE(
                        (SELECT Token_Drawer_EOD + Token_Sealed_EOD
                        FROM EndDates ed
                        WHERE ed.locations_id = t1.locations_id
                        AND ed.end_date < t1.entry_date  -- Get Total EOD for the most recent EOD before the start_date
                        ORDER BY ed.end_date DESC
                        LIMIT 1), 0
                    ) AS Variance,

                    -- Calculate the Variance_2 (Token_Bal_SOD - Total_SOD)
                    (t1.token_drawer + t1.token_sealed) - t1.token_bal AS Variance_2

                FROM 
                    cash_float_histories AS t1
                JOIN 
                    locations AS loc ON t1.locations_id = loc.id  -- Join the locations table to get the location_name
                LEFT JOIN 
                    cms_users AS u1 ON t1.created_by = u1.id  -- Join for SOD created_by to get user name
                LEFT JOIN 
                    cms_users AS u2 ON (SELECT Created_by_EOD FROM EndDates ed WHERE ed.locations_id = t1.locations_id 
                                        AND ed.end_date < t1.entry_date  -- Get Created_by for the most recent EOD before the start_date
                                        ORDER BY ed.end_date DESC  -- Ensure we get the most recent one
                                        LIMIT 1) = u2.id  -- Join for EOD created_by to get user name
                WHERE 
                    t1.float_types_id = 1  -- SOD records
                    AND t1.entry_date >= '2025-02-07'
                ORDER BY 
                    t1.entry_date;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS sod_vs_eod_variance_report;");
    }
}
