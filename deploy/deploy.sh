# recreate tmp dir
rm -rf ../tmp
mkdir ../tmp
touch ../tmp/sftp.log
mkdir ../tmp/silex_cache 

chmod a+rw ../tmp -R

# clean unit test and code coverage reports
rm -rf ../tests/coverage
rm -rf ../tests/reports
rm -f ../tests/testdox.*
rm -f ../tests/logfile.*


mkdir ../tests/coverage
mkdir ../tests/reports
chmod a+rw ../tests/coverage -R
chmod a+rw ../tests/reports -R 

