from fabric.api import env, local, run
from fabric.contrib.project import rsync_project

environments = {
    'hackathon': {
        'user': 'hackathon',
        'hosts': ['193.104.37.91'],
        'env_name': 'hackathon',
        'remote_dir': '/var/www/tripnwin_api',
        'permission_user': ''
    },
}

excluded_files = [
    '.DS_Store',
    '.git',
    '.htpasswd'
    '.idea',
    '*.pyc',
    '/config.php',
]


def hackathon():
    global remote_dir
    global permission_user

    env.update(environments['hackathon'])
    remote_dir = environments['hackathon']['remote_dir']
    permission_user = 'hackathon:hackathon'


def deploy():
    rsync()
    # fix_permissions()


def rsync():
    rsync_project(local_dir='./', remote_dir=remote_dir, exclude=excluded_files)


def fix_permissions():
    run('chown -R %s %s/*' % (permission_user,remote_dir))