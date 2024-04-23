#! /bin/bash
# shellcheck disable=SC2034

# define the target repo
DOCKER_REPO="${DOCKER_REPO:-moonbuggy2000/ntp-monitor}"

# default tag to build when no arguments provided
default_tag='latest'

# optional: tag(s) to build when 'all' is the argument
all_tags='latest'

# start the builder proper
. "hooks/.build.sh"
