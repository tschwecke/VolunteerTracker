rm -f package/volunteer.sql
cat Sql/Ddl.sql Sql/Data.sql Sql/StoredProcedures/*.sql >> package/volunteer.sql
