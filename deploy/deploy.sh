# recreate tmp dir
sudo rm -rf ../tmp
mkdir ../tmp
touch ../tmp/sftp.log
mkdir ../tmp/silex_cache 

chmod a+rw ../tmp -R

# clean unit test and code coverage reports
sudo rm -rf ../tests/coverage
sudo rm -rf ../tests/reports

mkdir ../tests/coverage
mkdir ../tests/reports

chmod a+rw ../tests/coverage -R
chmod a+rw ../tests/reports -R 
