#!/bin/bash
# Removes a known bad migration that was created with an empty table name.
# Safe to run - only deletes the specific problematic file.
rm -f database/migrations/2026_02_08_003437_create__table.php 2>/dev/null || true
