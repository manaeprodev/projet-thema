pipeline {
    agent none
    environment {
            DEFAULT_BRANCH = 'main'
            PROJECT_NAME='Predeect'
            GITLAB_PROJECT_ID='predeect1'
        }
    options {
          gitLabConnection('scm-mm')
          buildDiscarder(logRotator(numToKeepStr: '5'))
          disableConcurrentBuilds()
    }
    stages {
            stage('lint') {
               agent { docker { image 'mykiwi/phaudit:7.2' } }
                steps {
                    catchError(buildResult: 'SUCCESS', stageResult: 'FAILURE') {
                        sh 'parallel-lint --blame --exclude vendor .'
                    }
                }
            }

            stage('security') {
                agent { docker { image 'mykiwi/phaudit:7.2' } }
                steps {
                    catchError(buildResult: 'SUCCESS', stageResult: 'FAILURE') {
                        sh 'phpstan analyse --level=max .'
                    }
                }
            }
            stage('quality') {
                agent { docker { image 'mykiwi/phaudit:7.2' } }
                steps {
                    catchError(buildResult: 'SUCCESS', stageResult: 'FAILURE') {
                        sh 'phpmd . text cleancode'
                    }
                }
            }
        }
    }
